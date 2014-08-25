<?php

namespace Btn\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Btn\PageBundle\Entity\PageInterface;

/**
 * Page controller
 */
class PageController extends Controller
{
    /**
     * Finds and displays a one news
     *
     * @Route("/page/{page}", name="btn_page_page_show", requirements={"page" = "\d+"})
     */
    public function showAction($page)
    {
        if ($page instanceof PageInterface) {
        } elseif (is_int($page)) {
            $page = $this->get('btn_page.provider.page')->getRepository()->find($page);
        } else {
            throw new \Exception('Invalid input in showAction of PageController');
        }

        $backUrl = null;

        if ($url = $this->get('session')->get('_btn_slug')) {

            $backUrl = $this->generateUrl('_btn_slug', array('url' => $url));
        }

        $content        = array();
        $template       = '';

        $template = $page->getTemplate();

        if (!empty($template)) {
            $content   = @unserialize($page->getContent());
            $templates = $this->container->getParameter('btn_page.templates');
            $twigTpl   = isset($templates[$template]['template']) ? $templates[$template]['template'] : null;
            $fields    = isset($templates[$template]['fields']) ? $templates[$template]['fields'] : null;

            if (is_array($content) && $fields) {
                foreach ($content as $field => $value) {
                    if (isset($fields[$field]) && $fields[$field]['type'] === 'entity') {
                        $orderBy = null;
                        if (!empty($fields[$field]['query_builder']['orderby'])) {
                            $orderType = !empty($fields[$field]['query_builder']['type']) ?
                                $fields[$field]['query_builder']['type'] : 'ASC';
                            $orderBy = array($fields[$field]['query_builder']['orderby'] => $orderType);
                        }
                        $content[$field] =
                            $this->getDoctrine()
                                 ->getManager()
                                 ->getRepository($fields[$name]['class'])->findById($value, $orderBy);
                    }
                }
            }
        }

        return $this->render($twigTpl, array(
            'page' => $page,
            'backUrl' => $backUrl,
            'content' => $content,
            'template' => $template,
        ));
    }
}
