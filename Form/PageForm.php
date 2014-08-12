<?php

namespace Btn\PageBundle\Form;

use Btn\AdminBundle\Form\AbstractForm;
use Symfony\Component\Form\FormBuilderInterface;
use Btn\PageBundle\Form\EventListener\PageFormFactorySubscriber;

class PageForm extends AbstractForm
{
    /** @var \Btn\PageBundle\Form\EventListener\PageFormFactorySubscriber */
    protected $pageFormFactorySubscriber;

    /**
     *
     */
    public function __construct(PageFormFactorySubscriber $pageFormFactorySubscriber)
    {
        $this->pageFormFactorySubscriber = $pageFormFactorySubscriber;
    }

    /**
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->addEventSubscriber($this->pageFormFactorySubscriber);

        $builder
            ->add('save', $options['data']->getId() ? 'btn_save' : 'btn_create')
        ;
    }

    /**
     *
     */
    public function getName()
    {
        return 'btn_page_form_page';
    }
}
