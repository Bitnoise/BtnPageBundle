<?php

namespace Btn\PageBundle\Entity;

use Btn\PageBundle\Model\Page as BasePage;
use Doctrine\ORM\Mapping as ORM;

/**
 * Page class
 *
 * @ORM\Table(name="page")
 * @ORM\Entity
 */
class Page extends BasePage
{
}
