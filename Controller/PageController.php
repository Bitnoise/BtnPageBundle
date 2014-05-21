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
     */
    public function showAction(Page $page)
    {
        //default
        $backUrl = null;
        //resolve back to list url
        if ($url = $this->get('session')->get('_btn_slug')) {

            $backUrl = $this->generateUrl('_btn_slug', array('url' => $url));
        }

        $content        = array();
        $twigTmplName   = '';

        $template = $page->getTemplate();

        if(!empty($template)) {
            $content      = @unserialize($page->getContent());
            $templateConf = $this->container->getParameter('btn_pages');
            $twigTmplName = isset($templateConf['templates'][$template]['name']) ? $templateConf['templates'][$template]['name'] : null;
            $templateConf = isset($templateConf['templates'][$template]['fields']) ? $templateConf['templates'][$template]['fields'] : null;

            if(is_array($content) && $templateConf) {
                foreach ($content as $name => $value) {
                    if(isset($templateConf[$name]) && $templateConf[$name]['type'] === 'entity') {
                        $orderBy = null;
                        if (!empty($templateConf[$name]['query_builder']['orderby'])) {
                            $orderType = !empty($templateConf[$name]['query_builder']['type']) ?
                                $templateConf[$name]['query_builder']['type'] : 'ASC';
                            $orderBy = array($templateConf[$name]['query_builder']['orderby'] => $orderType);
                        }
                        $content[$name] =
                            $this->getDoctrine()
                                 ->getManager()
                                 ->getRepository($templateConf[$name]['class'])->findById($value, $orderBy);
                    }
                }
            }
        }

        return array('page' => $page, 'backUrl' => $backUrl, 'content' => $content, 'tmplName' => $twigTmplName);
    }
}
