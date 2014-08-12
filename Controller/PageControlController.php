<?php

namespace Btn\PageBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Btn\AdminBundle\Controller\AbstractControlController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Btn\AdminBundle\Annotation\EntityProvider;

/**
 * @Route("/page")
 * @EntityProvider("btn_page.provider.page")
 */
class PageControlController extends AbstractControlController
{
    /**
     * Lists all Page entities.
     *
     * @Route("/", name="btn_page_pagecontrol_index")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $repo     = $this->getEntityProvider()->getRepository();
        $entities = $repo->findAll();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $entities,
            $request->query->getInt('page', 1),
            $this->getPerPage()
        );

        return array(
            'pagination'  => $pagination,
        );
    }

    /**
     * @Route("/new", name="btn_page_pagecontrol_new", methods={"GET"})
     * @Route("/create", name="btn_page_pagecontrol_create", methods={"POST"})
     * @Template()
     */
    public function createAction(Request $request)
    {
        $entity = $this->getEntityProvider()->create();

        $form = $this->createForm('btn_page_form_page', $entity, array(
            'action' => $this->generateUrl('btn_page_pagecontrol_create'),
        ));

        if ($this->handleForm($form, $request)) {
            $this->setFlash('btn_admin.flash.created');

            return $this->redirect($this->generateUrl('btn_page_pagecontrol_edit', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * @Route("/{id}/edit", name="btn_page_pagecontrol_edit", requirements={"id" = "\d+"}, methods={"GET"})
     * @Route("/{id}/update", name="btn_page_pagecontrol_update", requirements={"id" = "\d+"}, methods={"POST"})
     * @Template()
     */
    public function updateAction(Request $request, $id)
    {
        $entity = $this->findEntityOr404($this->getEntityProvider()->getClass(), $id);

        $editForm   = $this->createForm('btn_page_form_page', $entity);

        $form = $this->createForm('btn_page_form_page', $entity, array(
            'action' => $this->generateUrl('btn_page_pagecontrol_update', array('id' => $id)),
        ));

        if ($this->handleForm($form, $request)) {
            $this->setFlash('btn_admin.flash.updated');

            return $this->redirect($this->generateUrl('btn_page_pagecontrol_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * @Route("/{id}/delete/{csrf_token}", name="btn_page_pagecontrol_delete", requirements={"id" = "\d+"}, methods={"GET"})
     */
    public function deleteAction(Request $request, $id, $csrf_token)
    {
        $this->validateCsrfTokenOrThrowException('btn_page_pagecontrol_delete', $csrf_token);

        $entityProvider = $this->getEntityProvider();
        $entity         = $this->findEntityOr404($id, $entityProvider->getClass());

        $entityProvider->delete($entity);

        $this->setFlash('btn_admin.flash.deleted');

        return $this->redirect($this->generateUrl('btn_page_pagecontrol_index'));
    }
}
