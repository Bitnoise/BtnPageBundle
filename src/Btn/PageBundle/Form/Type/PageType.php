<?php

namespace Btn\PageBundle\Form\Type;

use Btn\AdminBundle\Form\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class PageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'label'         => 'btn_page.form.type.page',
            'empty_value'   => 'btn_page.form.type.page.empty_value',
            'class'         => $this->class,
            'data_class'    => null,
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
    public function getParent()
    {
        return 'entity';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'btn_page';
    }
}
