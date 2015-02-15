<?php

namespace Btn\PageBundle\Provider;

use Btn\NodeBundle\Provider\NodeContentProviderInterface;
use Btn\PageBundle\Form\NodeContentType;

class PageNodeContentProvider implements NodeContentProviderInterface
{
    protected $configuration;

    /**
     *
     */
    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     *
     */
    public function isEnabled()
    {
        return $this->configuration['enabled'];
    }

    /**
     *
     */
    public function getForm()
    {
        return new NodeContentType();
    }

    /**
     *
     */
    public function resolveRoute($formData = array())
    {
        return isset($formData['page']) ? $this->configuration['route_name'] : null;
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
