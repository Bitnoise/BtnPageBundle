<?php
namespace Btn\PageBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CustomFormFactorySubscriber implements EventSubscriberInterface
{
    /**
     * default template name
     */
    const DEFAULT_TEMPLATE_NAME = 'show';

    /**
     * $templates key value list of templates
     * @var array
     */
    private $templates     = array();

    /**
     * $builder
     * @var CustomFormBuilder
     */
    private $formBuilder;

    /**
     * @param array  $bundleConf
     * @param router $router
     */
    public function __construct($bundleConf = array(), $router, $em)
    {
        if (!is_array($bundleConf)) {
            throw new \Exception("Bundle configuration should be an array!");
        }

        /* set templates config */
        $this->templatesConf = isset($bundleConf['templates']) ? $bundleConf['templates'] : array();

        /* and simplified key => value version for Symfony2 select purpose */
        $this->templates     = $this->getSimpleArrayTemplates();

        /* create custom form builder for this factory */
        $this->formBuilder  = new CustomFormBuilder($this->templates, $em);

        /* prepare ckeditor config */
        if (isset($bundleConf['ckeditor_conf'])) {
            /* url of content with image browser */
            $bundleConf['ckeditor_conf']['config']['filebrowserImageBrowseUrl']
                = $router->generate('cp_media_list_modal', array('separated' => TRUE));

            $this->formBuilder->setCkeditor($bundleConf['ckeditor_conf']);
        }
    }

    /**
     * getSubscribedEvents
     *
     * Tells the dispatcher that you want to listen on the form.pre_set_data
     * event and that the preSetData method should be called.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT   => 'preSubmit',
            FormEvents::POST_BIND    => 'postBind'
        );
    }

    /**
     * checkTemplates check if template configs are declared and template isn't default one
     * @param  [type] $template
     * @return [type]
     */
    private function checkTemplates($template)
    {
        return
            !empty($this->templatesConf) &&
            !empty($template) && $template !== self::DEFAULT_TEMPLATE_NAME &&
            isset($this->templatesConf[$template]);
    }

    public function preSetData(FormEvent $event)
    {
        $this->formBuilder->setForm($event->getForm());

        /* add template select field */
        if (!empty($this->templates)) {
            $this->formBuilder->setTemplateSelect($this->templates);
        }

        /* get form data -> entity Page */
        $data = $event->getData();

        /* if its a new entity remove content field */
        if (!$data || !$data->getId()) {
            $this->formBuilder->removeContentField();
        } else {
            /* get template name to use */
            $template = $data->getTemplate();

            /* prepare fields based on conf */
            if ($this->checkTemplates($template)) {
                $templateData = $this->templatesConf[$template];

                /* unserialize from content field */
                $this->formBuilder->setContent($data->getContent());

                /* add to form custom fields from template config */
                foreach ($templateData['fields'] as $field => $params) {
                    $this->formBuilder->addField($field, $params);
                }

                /* remove content field */
                if (isset($templateData['hide_content']) && $templateData['hide_content']) {
                    $this->formBuilder->removeContentField();
                }
            } else {
                /* render default form */
                $this->formBuilder->addDefault();
            }
        }
    }

    /**
     * preSubmit serialize data from custom fields to the content field
     * @param  FormEvent $event
     * @return void
     */
    public function preSubmit(FormEvent $event)
    {
        //get post data
        $data = $event->getData();

        //get page entity
        $page = $event->getForm()->getData();

        if (isset($data['template']) && $data['template'] !== 'show') {
            unset($data['title']);
            unset($data['template']);

            $page->setContent(serialize($data));
        }
    }

    /**
     * postBind
     * @param  FormEvent $event
     * @return array
     */
    public function postBind(FormEvent $event)
    {
        $data = $event->getData();

        return $data;
    }

    /**
     * getSimpleArrayTemplates prepare simple array for templates select field
     * @param  array $templatesConf
     * @return array
     */
    private function getSimpleArrayTemplates($templatesConf = array())
    {
        // create simple array key => val
        $templates = array();

        if (!empty($this->templatesConf)) {
            foreach ($this->templatesConf as $key => $value) {
                $templates[$key] = $value['name'];
            }
        }

        return $templates;
    }
}
