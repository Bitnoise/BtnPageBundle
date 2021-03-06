<?php
namespace Btn\PageBundle\Form\EventListener;

use Symfony\Component\Form\Form;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Btn\BaseBundle\Provider\EntityProviderInterface;
use Doctrine\ORM\EntityManager;

class PageFormBuilder
{
    /**
     * $templates array of objects with templates configuration.
     *
     * @var array
     */
    private $templates = array();

    /**
     * $form.
     *
     * @var \Symfony\Component\Form\Form
     */
    private $form = null;

    /**
     * $content content of extra fields.
     *
     * @var array
     */
    private $content;

    /** @var Btn\BaseBundle\Provider\EntityProviderInterface $mediaProvider */
    private $mediaProvider;

    /**
     *
     */
    public function __construct(array $templates, EntityManager $em)
    {
        $this->em        = $em;
        $this->templates = $templates;
    }

    /**
     * @param EntityProviderInterface $mediaProvider
     */
    public function setMediaProvider(EntityProviderInterface $mediaProvider)
    {
        $this->mediaProvider = $mediaProvider;
    }

    /**
     *
     */
    public function setContent($content)
    {
        $this->content = @unserialize($content);
    }

    /**
     * setForm set form object to work on.
     *
     * @param Form $form
     */
    public function setForm(Form $form)
    {
        $this->form = $form;

        /* set default form title */
        $this->setTitle();
    }

    /**
     * setTitle set default form title.
     */
    public function setTitle()
    {
        $this->form->add('title', null, array(
            'label' => 'btn_page.form.page.title',
        ));
    }

    /**
     * setTemplateSelect add template select to form.
     *
     * @param [type] $templates
     */
    public function setTemplateSelect($templates)
    {
        $this->form->add('template', 'btn_select2_choice', array(
            'label'       => 'btn_page.template',
            'empty_value' => 'btn_page.template_empty_value',
            'choices'     => $templates,
            'attr'        => array('class' => 'on-template-change'),
            'mapped'      => true,
        ));
    }

    public function removeContentField()
    {
        $this->form->remove('content');
    }

    private function processParams(&$type, &$params)
    {
        switch ($type) {
            case 'entity':
            case 'btn_select2_entity':
                $params['query_builder'] = $this->getSelectQueryFunction($params);
                break;

            default:
                break;
        }
    }

    private function getSelectQueryFunction($params)
    {
        $orderBy    = sprintf('e.%s', $params['query_builder']['orderby']);
        $orderType  = $params['query_builder']['type'];
        $andwhere = isset($params['query_builder']['andwhere']) ? $params['query_builder']['andwhere'] : array();

        foreach ($andwhere as $i => $conditions) {
            if (count($conditions) === 2) {
                list($key, $value) = $conditions;
                $key               = sprintf('e.%s', $key);
                $andwhere[$i]      = array($key, $value);
            }
        }

        return function (EntityRepository $em) use ($orderBy, $orderType, $andwhere) {
            $qb = $em->createQueryBuilder('e');

            foreach ($andwhere as $criteria) {
                $qb->andwhere(implode('=', $criteria));
            }

            $qb->orderBy($orderBy, $orderType);

            return $qb;
        };
    }

    private function getFieldContent($field, $type, $params)
    {
        if (!empty($this->content[$field])) {
            $content = $this->content[$field];

            if ($type === 'entity' || $type === 'btn_select2_entity') {
                /* if entity field was normal select (<select>) */
                if (is_string($content)) {
                    $content = $this->em->getRepository($params['class'])->findOneById($content);
                } else {
                    /* if entity field was multiselect */
                    $content = new ArrayCollection(
                        $this->em->getRepository($params['class'])->findById($content)
                    );
                }
            } elseif ($type === 'btn_media') {
                $content = $this->mediaProvider->getRepository()->findOneById($this->content[$field]);
            }

            return $content;
        }
    }

    /**
     * addField add passed field to form, also with custom params.
     *
     * @param string $field
     * @param array  $params
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
     * addDefault add default BtnWysiwyg as content field.
     */
    public function addDefault($clearContent = false)
    {
        $options = array(
            'label' => 'btn_page.form.page.content',
        );

        if ($clearContent) {
            $options['data'] = '';
        }

        $this->form->add('content', 'btn_wysiwyg', $options);
    }
}
