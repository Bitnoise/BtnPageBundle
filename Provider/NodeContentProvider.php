<?php

namespace Btn\PageBundle\Service;

use Btn\NodesBundle\Service\NodeContentProviderInterface;
use Btn\PageBundle\Form\NodeContentType;

/**
 *
 */
class NodeContentProvider implements NodeContentProviderInterface
{

    private $router;
    private $em;

    public function __construct($router, $em)
    {
        $this->router = $router;
        $this->em     = $em;
    }

    public function getForm()
    {
        $pages = $this->em->getRepository('BtnPageBundle:Page')->findAll();

        $data = array();
        foreach ($pages as $page) {
            $data[$page->getId()] = $page->getTitle();
        }

        return new NodeContentType($data);
    }

    public function resolveRoute($formData = array())
    {
        return 'page_show';
    }

    public function resolveRouteParameters($formData = array())
    {
        return array('id' => $formData['page']);
    }

    public function resolveControlRoute($formData = array())
    {
        return 'cp_page_edit';
    }

    public function resolveControlRouteParameters($formData = array())
    {
        return array('id' => $formData['page']);
    }
}
