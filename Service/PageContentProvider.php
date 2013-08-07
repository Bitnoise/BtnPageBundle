<?php

namespace Btn\PageBundle\Service;

use Btn\NodesBundle\Service\NodeContentProviderInterface;
use Btn\PageBundle\Form\NodeContentType;

/**
*
*
*/
class PageContentProvider implements NodeContentProviderInterface
{

    private $router;

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
            //depracated
            // $data[$this->router->generate('page_show', array('id' => $page->getId()))] = $page->getTitle();
            $data[$page->getId()] = $page->getTitle();
        }

        return new NodeContentType($data);
    }

    public function resolveRouteName($formData = array())
    {
        //resolve from request and return the route name for node in nodes tree
        $routeName = $this->router->generate('page_show', array('id' => $formData['page']));

        return $routeName;
    }


    public function resolveControlRouteName($formData = array())
    {
        //resolve from request and return the route name for node in nodes tree
        $routeName = $this->router->generate('cp_page_edit', array('id' => $formData['page']));

        return $routeName;
    }
}