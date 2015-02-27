<?php

namespace Btn\PageBundle\Form\Type;

use Btn\AdminBundle\Form\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Btn\PageBundle\Form\DataTransformer\IdToPageTransformer;
use Btn\PageBundle\Form\DataTransformer\IdToPageQuietTransformer;
use Btn\PageBundle\Model\PageInterface;
use Doctrine\ORM\EntityRepository;

class PageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        if (!empty($options['data_class'])) {
            // add view transformer duo form exception
            $builder->addViewTransformer(new IdToPageTransformer($this->entityProvider));
        } else {
            $builder->addModelTransformer(new IdToPageQuietTransformer($this->entityProvider));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(array(
            'label'         => 'btn_page.form.type.page.label',
            'empty_value'   => 'btn_page.form.type.page.empty_value',
            'class'         => $this->class,
            'data_class'    => $this->class,
            'query_builder' => function (EntityRepository $em) {
                return $em
                    ->createQueryBuilder('p')
                    ->orderBy('p.title', 'ASC');
            },
            'property' => 'title',
            'required' => false,
            'expanded' => false,
            'multiple' => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        // correct value from data transformer for choice to select coreclty
        if (!empty($options['data_class']) && $view->vars['value'] instanceof PageInterface) {
            $view->vars['value'] = (string) $view->vars['value']->getId();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'btn_select2_entity';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'btn_page';
    }
}
