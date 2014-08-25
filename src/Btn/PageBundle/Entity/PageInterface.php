<?php

namespace Btn\PageBundle\Entity;

interface PageInterface
{
    public function getId();
    public function setContent($content);
    public function getContent();
    public function setTitle($title);
    public function getTitle();
    public function setTemplate($template);
    public function getTemplate();
}
