<?php

namespace Btn\PageBundle\Helper;

use Btn\PageBundle\Model\PageInterface;
use Doctrine\ORM\EntityManager;
use Btn\BaseBundle\Provider\EntityProviderInterface;

class PageHelper
{
    /** @var array $templates */
    private $templates;
    /** @var EntityManager $entityManager */
    private $entityManager;
    /** @var EntityProviderInterface $mediaProvider */
    private $mediaProvider;

    /**
     *
     */
    public function __construct(
        array $templates,
        EntityManager $entityManager,
        EntityProviderInterface $mediaProvider = null
    ) {
        $this->templates     = $templates;
        $this->entityManager = $entityManager;
        $this->mediaProvider = $mediaProvider;
    }

    /**
     *
     */
    public function getTemplates()
    {
        return $this->templates;
    }

    /**
     *
     */
    public function getTemplateConfig($template)
    {
        $templates = $this->getTemplates();

        return array_key_exists($template, $templates) ? $templates[$template] : null;
    }

    /**
     *
     */
    public function getTemplateConfigField($template, $field)
    {
        $templateConfig = $this->getTemplateConfig($template);

        return ($templateConfig && isset($templateConfig[$field])) ? $templateConfig[$field] : null;
    }

    /**
     *
     */
    public function getTemplateFields($template)
    {
        return $this->getTemplateConfigField($template, 'fields');
    }

    /**
     *
     */
    public function getTemplateTwigTpl($template)
    {
        return $this->getTemplateConfigField($template, 'template');
    }

    /**
     *
     */
    public function getContent(PageInterface $page)
    {
        $template  = $page->getTemplate();
        $content   = @unserialize($page->getContent());
        $fields    = $this->getTemplateFields($template);

        if (is_array($content) && $fields) {
            foreach ($content as $field => $value) {
                if (isset($fields[$field]) && !empty($fields[$field]['type'])) {
                    $content[$field] = $this->getFieldValue($fields[$field], $value);
                }
            }
        }

        return $content;
    }

    /**
     *
     */
    private function getFieldValue(array $fieldConfig, $value)
    {
        switch ($fieldConfig['type']) {
            case 'btn_media':
                return $this->mediaProvider->getRepository()->findOneById($value);
                break;
            case 'entity':
            case 'btn_select2_entity':
                $orderBy = null;
                if (!empty($fieldConfig['query_builder']['orderby'])) {
                    $orderType = !empty($fieldConfig['query_builder']['type']) ?
                        $fieldConfig['query_builder']['type'] : 'ASC';
                    $orderBy = array($fieldConfig['query_builder']['orderby'] => $orderType);
                }
                $method = 'findOneById';
                if (isset($fieldConfig['multiple']) && true === $fieldConfig['multiple']) {
                    $method = 'findById';
                }

                return $this->entityManager->getRepository($fieldConfig['class'])->$method($value, $orderBy);
                break;
            default:
                return $value;
                break;
        }
    }
}
