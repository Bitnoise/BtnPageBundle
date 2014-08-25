<?php

namespace Btn\PageBundle\Form;

use Btn\AdminBundle\Form\AbstractForm;
use Symfony\Component\Form\FormBuilderInterface;
use Btn\PageBundle\Form\EventListener\PageFormFactorySubscriber;

class PageControlForm extends AbstractForm
{
    /** @var \Btn\PageBundle\Form\EventListener\PageFormFactorySubscriber */
    protected $pageFormFactorySubscriber;

    /**
     *
     */
    public function setPageFormFactorySubscriber(PageFormFactorySubscriber $pageFormFactorySubscriber)
    {
        $this->pageFormFactorySubscriber = $pageFormFactorySubscriber;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber($this->pageFormFactorySubscriber);

        parent::buildForm($builder, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'btn_page_form_page_control';
    }
}
