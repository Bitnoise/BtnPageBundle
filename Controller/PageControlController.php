<?php

namespace Btn\PageBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Btn\PageBundle\Entity\Page;
use Btn\PageBundle\Form\PageType;

/**
 * Page controller.
 *
 * @Route("/page")
 */
class PageControlController extends Controller
{
    /**
     * Lists all Page entities.
     *
     * @Route("/", name="cp_page")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BtnPageBundle:Page')->findAll();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $entities,
            $this->get('request')->query->get('page', 1),
            10
        );

        return array(
            'pagination' => $pagination,
        );
    }

    /**
     * Finds and displays a Page entity.
     *
     * @Route("/{id}/show", name="cp_page_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BtnPageBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Page entity.
     *
     * @Route("/new", name="cp_page_new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $entity = new Page();
        $form   = $this->createForm('btn_pagebundle_pagetype', $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Page entity.
     *
     * @Route("/create", name="cp_page_create")
     * @Method("POST")
     * @Template("BtnPageBundle:PageControl:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Page();
        $form   = $this->createForm('btn_pagebundle_pagetype', $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $msg = $this->get('translator')->trans('btn_admin.flash.saved');
            $this->getRequest()->getSession()->getFlashBag()->set('success', $msg);

            return $this->redirect($this->generateUrl('cp_page_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Page entity.
     *
     * @Route("/{id}/edit", name="cp_page_edit")
     * @Template()
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BtnPageBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $editForm   = $this->createForm('btn_pagebundle_pagetype', $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Page entity.
     *
     * @Route("/{id}/update", name="cp_page_update")
     * @Method("POST")
     * @Template("BtnPageBundle:PageControl:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BtnPageBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }
        $editForm   = $this->createForm('btn_pagebundle_pagetype', $entity);
        $deleteForm = $this->createDeleteForm($id);

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            $msg = $this->get('translator')->trans('btn_admin.flash.saved');
            $this->getRequest()->getSession()->getFlashBag()->set('success', $msg);

            return $this->redirect($this->generateUrl('cp_page_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Page entity.
     *
     * @Route("/{id}/delete", name="cp_page_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BtnPageBundle:Page')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Page entity.');
            }

            $em->remove($entity);
            $em->flush();

            $msg = $this->get('translator')->trans('btn_admin.flash.deleted');
            $this->getRequest()->getSession()->getFlashBag()->set('success', $msg);
        }

        return $this->redirect($this->generateUrl('cp_page'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    //depricated -> don't use it
    private function resolveFormClass($template)
    {
        //get ckeditor url
        $ckeditor = $this->generateUrl('btn_media_mediacontrol_listmodal', array('separated' => true));
        if ($template !== null && $template !== '') {
            //get templates config from params
            $templatesConf = $this->get('service_container')->getParameter('btn_pages.templates');
            //get real namespace, don't use _namespace_
            $matches    = array();
            $controller = $request->get('_controller');
            $matches    = explode('\\', $controller);
            //set form class namespace path
            $formClass = '\\' . $matches[0] . '\\' .  $matches[1] . '\\Form\\Page' . ucfirst($template) . 'Type';
            //check if class exsists
            if (!class_exists($formClass)) {
                throw $this->createNotFoundException('Unable to find class: ' . $formClass);
            }
            $form = new $formClass($ckeditor, $this->getSimpleArrayTemplates($templatesConf));
        } else {
            // get default PageType form class
            $form = new PageType($ckeditor, $this->getSimpleArrayTemplates($templatesConf));
        }
        //TODO : do we need to load custom template file ?
        return $form;
    }
}
