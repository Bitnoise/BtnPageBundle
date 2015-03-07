<?php

namespace Btn\PageBundle\Controller;

use Symfony\Component\HttpFoundation\request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Btn\PageBundle\Model\PageInterface;

/**
 * Page controller.
 */
class PageController extends Controller
{
    /**
     * Finds and displays a one news.
     *
     * @Route("/_page/{id}", name="btn_page_page_show", requirements={"id" = "\d+"})
     */
    public function showAction(Request $request, $id)
    {
        if ($id instanceof PageInterface) {
            $page = $id;
        } elseif (is_numeric($id)) {
            $page = $this->get('btn_page.provider.page')->getRepository()->find($id);
        } else {
            throw new \Exception(sprintf('Invalid input in showAction of "%s"', __CLASS__));
        }

        $pageHelper = $this->get('btn_page.helper.page');
        $twigTpl  = null;
        $backUrl  = null;
        $content  = array();
        $template = null;

        if (($url = $this->get('session')->get('_btn_node'))) {
            $backUrl = $this->generateUrl('_btn_node', array('url' => $url));
        }

        if ($page && $page->getTemplate()) {
            $template = $page->getTemplate();
            $content  = $pageHelper->getContent($page);
            $twigTpl  = $pageHelper->getTemplateTwigTpl($template);
        }

        return $this->render($twigTpl, array(
            'node'     => $request->attributes->get('node', null),
            'page'     => $page,
            'backUrl'  => $backUrl,
            'content'  => $content,
            'template' => $template,
        ));
    }
}
