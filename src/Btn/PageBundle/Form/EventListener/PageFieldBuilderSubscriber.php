<?php
namespace Btn\PageBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PageFieldBuilderSubscriber implements EventSubscriberInterface
{
    protected $templates     = array();
    protected $templatesConf = array();

    /**
     * @param array $bundleConf
     */
    public function __construct(array $templatesConf = array())
    {
        $this->templatesConf = $templatesConf;
        //prepare simple array for templates select field
        $this->templates = $this->getSimpleArrayTemplates($this->templatesConf);
    }

    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT   => 'preSubmit',
            FormEvents::POST_BIND    => 'postBind',
        );
    }

    public function preSetData(FormEvent $event)
    {
        //get form object
        $form = $event->getForm();

        //add template select field
        if (!empty($this->templates)) {
            $form->add('template', 'choice', array(
                    'choices' => $this->templates,
                    'attr' => array('class' => 'on-template-change'),
                ));
        }
        //add title select field
        $form->add('title');

        //get form data -> entity Page
        $data = $event->getData();
        //if its a new entity remove content field
        if (!$data || !$data->getId()) {
            $form->remove('content');
        } else {
            $template = $data->getTemplate();
            //prepare fields based on conf
            if (!empty($this->templatesConf) && $template && $template !== ''
                && $template !== PageFormFactorySubscriber::DEFAULT_TEMPLATE_NAME) {
                if (isset($this->templatesConf[$template])) {
                    $templateData = $this->templatesConf[$template];
                    //unserialize from content field
                    $content = unserialize($data->getContent());
                    foreach ($templateData['fields'] as $fieldName => $params) {
                        //set data to the field
                        if (isset($content[$fieldName])) {
                            $params['data'] = $content[$fieldName];
                        }
                        $type = $params['type'];
                        //unset type key to avoid field creation error
                        unset($params['type']);
                        //add custom field to the form
                        $form->add($fieldName, $type, $params);
                    }
                    //remove content field
                    if (isset($templateData['hide_content']) && $templateData['hide_content']) {
                        $form->remove('content');
                    }
                }
            } else {
                //render default form
                $form->add('content', 'btn_wysiwyg');
            }
        }
    }

    /*
    * serialize data from custom fields to the content field
    */
    public function preSubmit(FormEvent $event)
    {
        //get post data
        $data = $event->getData();
        //get page entity
        $page = $event->getForm()->getData();
        if ($data['template'] !== 'show') {
            unset($data['title']);
            unset($data['template']);
            $page->setContent(serialize($data));
        }
    }

    public function postBind(FormEvent $event)
    {
        $data = $event->getData();
    }

    private function getSimpleArrayTemplates($templatesConf = array())
    {
        // create simple array key => val
        $templates = array();
        if (!empty($templatesConf)) {
            foreach ($templatesConf as $key => $value) {
                $templates[$key] = $value['name'];
            }
        }

        return $templates;
    }
}
