<?php

namespace Btn\PageBundle\Provider;

use Btn\NodeBundle\Provider\NodeContentProviderInterface;
use Btn\PageBundle\Form\NodeContentType;
use Btn\BaseBundle\Provider\EntityProviderInterface;

class NodeContentProvider implements NodeContentProviderInterface
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
        return 'btn_page_page_show';
    }

    /**
     *
     */
    public function resolveRouteParameters($formData = array())
    {
        return array('id' => $formData['page']);
    }

    /**
     *
     */
    public function resolveControlRoute($formData = array())
    {
        return 'btn_page_pagecontrol_edit';
    }

    /**
     *
     */
    public function resolveControlRouteParameters($formData = array())
    {
        return array('id' => $formData['page']);
    }

    /**
     *
     */
    public function getName()
    {
        return 'btn_page.node_content_provider';
    }
}
