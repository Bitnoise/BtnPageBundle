<?php
namespace Btn\PageBundle\Form\EventListener;

use Symfony\Component\Form\Form;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

class CustomFormBuilder
{
    /**
     * $templatesConf array of objects with templates configuration
     * @var array
     */
    private $templates = array();

    /**
     * $form
     * @var \Symfony\Component\Form\Form
     */
    private $form = NULL;

    /**
     * $ckeditor flag to determine we should use CKEditor or not
     * @var boolean
     */
    private $ckeditor = FALSE;

    /**
     * $ckeditorConf description
     * @var array
     */
    private $ckeditorConf  = array();

    /**
     * $content content of extra fields
     * @var array
     */
    private $content;

    /**
     * __construct
     * @param array $templates
     * @param array $ckeditorConf
     */
    public function __construct($templates, $em, $ckeditorConf = NULL)
    {
        $this->em = $em;
        $this->templates = $templates;
    }

    public function setContent($content)
    {
        $this->content = @unserialize($content);
    }

    /**
     * setCkeditor set CKEditor config and flag
     * @param array $config
     */
    public function setCkeditor($config = NULL)
    {
        if(!is_array($config)) {
            throw new Exception("CKEditor config should be an array.", 1);
        }

        $this->ckeditor     = TRUE;
        $this->ckeditorConf = $config;
    }

    /**
     * setForm set form object to work on
     * @param Form $form
     */
    public function setForm(Form $form)
    {
        $this->form = $form;

        /* set default form title */
        $this->setTitle();
    }

    /**
     * setTitle set default form title
     */
    public function setTitle()
    {
        $this->form->add('title');
    }

    /**
     * setTemplateSelect add template select to form
     * @param [type] $templates
     */
    public function setTemplateSelect($templates)
    {
        $this->form->add('template', 'choice', array(
            'label'     => 'page.template',
            'choices'   => $templates,
            'attr'      => array('class' => 'on-template-change'),
            'mapped'    => TRUE,
            'empty_value' => 'page.template_empty_value'
        ));
    }

    public function removeContentField()
    {
        $this->form->remove('content');
    }

    private function processParams(&$type, &$params)
    {
        switch ($type) {
            case 'ckeditor':
                if ($this->ckeditor) {
                    $params = array_merge($params, $this->ckeditorConf);
                } else {
                    $type = 'textarea';
                }

                break;

            case 'entity':
                $params['query_builder'] = $this->getSelectQueryFunction($params);
                break;

            default:
                break;
        }

    }

    private function getSelectQueryFunction($params)
    {
        $orderBy    = sprintf('t.%s', $params['query_builder']['orderby']);
        $orderType  = $params['query_builder']['type'];

        return function(EntityRepository $em) use ($orderBy, $orderType) {
            return $em
                ->createQueryBuilder('t')
                ->orderBy($orderBy, $orderType);
        };
    }

    private function getFieldContent($field, $type, $params)
    {
        if (!empty($this->content[$field])) {
            $content = $this->content[$field];

            if($type === 'entity') {
                /* if entity field was normal select (<select>) */
                if(is_string($content)) {
                    $content = $this->em->getRepository($params['class'])->findOneById($content);
                }
                else {
                    /* if entity field was multiselect */
                    $content = new ArrayCollection(
                        $this->em->getRepository($params['class'])->findById($content)
                    );
                }
            }

            return $content;
        }
    }

    /**
     * addField add passed field to form, also with custom params
     * @param string $field
     * @param array $params
     */
    public function addField($field, $params)
    {
        $type = $params['type'];

        /* unset type key to avoid field creation error */
        unset($params['type']);

        /* set data to the field */
        $params['data'] = $this->getFieldContent($field, $type, $params);

        /* do extra things with passed parameters */
        $this->processParams($type, $params);

        /* add custom field to the form */
        $this->form->add($field, $type, $params);
    }

    /**
     * addDefault add default CKE Editor as content field
     */
    public function addDefault($clearContent = FALSE)
    {
        if($clearContent) {
            $this->ckeditorConf['data'] = '';
        }

        $this->form->add('content', 'ckeditor', $this->ckeditorConf);
    }
}
