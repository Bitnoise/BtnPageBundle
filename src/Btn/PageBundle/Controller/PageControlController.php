<?php

namespace Btn\PageBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Btn\AdminBundle\Controller\AbstractControlController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Btn\AdminBundle\Controller\CrudController;
use Btn\AdminBundle\Annotation\Crud;

/**
 * @Route("/page")
 * @Crud()
 */
class PageControlController extends CrudController
{
}
