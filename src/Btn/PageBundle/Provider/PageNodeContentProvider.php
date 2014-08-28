<?php

namespace Btn\PageBundle\Provider;

use Btn\NodeBundle\Provider\NodeContentProviderInterface;
use Btn\PageBundle\Form\NodeContentType;
use Btn\BaseBundle\Provider\EntityProviderInterface;

class PageNodeContentProvider implements NodeContentProviderInterface
{
    /** @var \Btn\BaseBundle\Provider\EntityProviderInterface $entityProvider */
    protected $entityProvider;

    /**
     *
     */
    public function __construct(EntityProviderInterface $entityProvider)
    {
        $this->entityProvider = $entityProvider;
    }

    /**
     *
     */
    public function isEnabled()
    {
        return true;
    }

    /**
     *
     */
    public function getForm()
    {
        $pages = $this->entityProvider->getRepository()->findAll();

        $data = array();
        foreach ($pages as $page) {
            $data[$page->getId()] = $page->getTitle();
        }

        return new NodeContentType($data);
    }

    /**
     *
     */
    public function resolveRoute($formData = array())
    {
        return isset($formData['page']) ? 'btn_page_page_show' : null;
    }

    /**
     *
     */
    public function resolveRouteParameters($formData = array())
    {
        return isset($formData['page']) ? array('id' => $formData['page']) : array();
    }

    /**
     *
     */
    public function resolveControlRoute($formData = array())
    {
        return isset($formData['page']) ? 'btn_page_pagecontrol_edit' : null;
    }

    /**
     *
     */
    public function resolveControlRouteParameters($formData = array())
    {
        return isset($formData['page']) ? array('id' => $formData['page']) : array();
    }

    /**
     *
     */
    public function getName()
    {
        return 'btn_page.page_node_content_provider.name';
    }
}
