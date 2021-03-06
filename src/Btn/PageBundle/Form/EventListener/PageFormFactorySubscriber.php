<?php
namespace Btn\PageBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Btn\BaseBundle\Provider\EntityProviderInterface;
use Doctrine\ORM\EntityManager;

class PageFormFactorySubscriber implements EventSubscriberInterface
{
    /**
     * default template name.
     */
    const DEFAULT_TEMPLATE_NAME = 'show';

    /**
     * $templates key value list of templates.
     *
     * @var array
     */
    private $templates = array();

    private $templatesConf;

    /**
     * @param array         $templatesConf
     * @param router        $router
     * @param EntityManager $em
     */
    public function __construct(array $templatesConf, UrlGeneratorInterface $router, EntityManager $em)
    {
        /* set templates config */
        $this->templatesConf = $templatesConf;

        /* and simplified key => value version for Symfony2 select purpose */
        $this->templates     = $this->getSimpleArrayTemplates();

        /* create custom form builder for this factory */
        $this->formBuilder   = new PageFormBuilder($this->templates, $em);
    }

    /**
     * @param EntityProviderInterface $mediaProvider
     */
    public function setMediaProvider(EntityProviderInterface $mediaProvider)
    {
        $this->formBuilder->setMediaProvider($mediaProvider);
    }

    /**
     * getSubscribedEvents.
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
        );
    }

    /**
     * checkTemplates check if template configs are declared and template isn't default one.
     */
    private function checkTemplates($template)
    {
        return
            !empty($this->templatesConf) &&
            !empty($template) && $template !== self::DEFAULT_TEMPLATE_NAME &&
            isset($this->templatesConf[$template]);
    }

    /**
     *
     */
    public function preSetData(FormEvent $event)
    {
        $this->formBuilder->setForm($event->getForm());

        /* add template select field */
        if (!empty($this->templates) && !$event->getData()->getId()) {
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
     * preSubmit serialize data from custom fields to the content field.
     *
     * @param FormEvent $event
     */
    public function preSubmit(FormEvent $event)
    {
        //get post data
        $data = $event->getData();

        //get page entity
        $page = $event->getForm()->getData();

        if (is_array($data)) {
            unset($data['title']);
            unset($data['template']);
            unset($data['save']);

            $page->setContent(serialize($data));
        }
    }

    /**
     * getSimpleArrayTemplates prepare simple array for templates select field.
     *
     * @param array $templatesConf
     *
     * @return array
     */
    private function getSimpleArrayTemplates($templatesConf = array())
    {
        // create simple array key => val
        $templates = array();

        if (!empty($this->templatesConf)) {
            foreach ($this->templatesConf as $key => $value) {
                $templates[$key] = !empty($value['title']) ? $value['title'] : $value['template'];
            }
        }

        return $templates;
    }
}
