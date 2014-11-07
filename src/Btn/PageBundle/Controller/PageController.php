<?php

namespace Btn\PageBundle\Controller;

use Symfony\Component\HttpFoundation\request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Btn\PageBundle\Model\PageInterface;

/**
 * Page controller
 */
class PageController extends Controller
{
    /**
     * Finds and displays a one news
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

        $backUrl = null;

        if ($url = $this->get('session')->get('_btn_node')) {
            $backUrl = $this->generateUrl('_btn_node', array('url' => $url));
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
                    if (isset($fields[$field]) && !empty($fields[$field]['type'])) {
                        switch ($fields[$field]['type']) {
                            case 'btn_media':
                                $content[$field] = $this->get('btn_media.provider.media')->getRepository()->findOneById($value);
                                break;
                            case 'entity':
                                $orderBy = null;
                                if (!empty($fields[$field]['query_builder']['orderby'])) {
                                    $orderType = !empty($fields[$field]['query_builder']['type']) ?
                                        $fields[$field]['query_builder']['type'] : 'ASC';
                                    $orderBy = array($fields[$field]['query_builder']['orderby'] => $orderType);
                                }
                                $content[$field] =
                                    $this->getDoctrine()
                                         ->getManager()
                                         ->getRepository($fields[$field]['class'])->findOneById($value, $orderBy);
                                break;
                            default:
                                break;
                        }
                    }
                }
            }
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
