<?php

namespace Btn\PageBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Abstract Page class
 */
abstract class Page
{
    /**
     * Unique id of the page
     *
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Page title
     *
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    protected $title = 'title';

    /**
     * Page content
     *
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    protected $content = 'content';

    /**
     * Page content
     *
     * @var string
     *
     * @ORM\Column(name="template", type="string", length=255, nullable=true)
     */
    protected $template;

    /**
     * Constructor.
     */
    public function __construct()
    {

    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set content
     *
     * @param  string $content
     * @return Page
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set title
     *
     * @param  string $title
     * @return Page
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set template
     *
     * @param  string $template
     * @return Page
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }
}
