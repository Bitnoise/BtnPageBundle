<?php

namespace Btn\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Btn\PageBundle\Entity\Page;

/**
 * Page controller
 */
class PageController extends Controller
{

    /**
     * Finds and displays a one news
     *
     * @Route("/page/{id}", name="page_show")
     * @Template()
     */
    public function showAction(Page $page)
    {
        //default
        $backUrl = null;
        //resolve back to list url
        if ($url = $this->get('session')->get('_btn_slug')) {

            $backUrl = $this->generateUrl('_btn_slug', array('url' => $url));
        }
        return array('page' => $page, 'backUrl' => $backUrl);
    }
}