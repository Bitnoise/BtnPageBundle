<?php

namespace Btn\PageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class NodeContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('page', 'btn_page', array(
                'data_class'  => null,
                'label'       => 'btn_page.page_node_content_provider.label',
                'empty_value' => 'btn_page.page_node_content_provider.empty_value',
                'ajax_reload' =>  true,
                'constraints' => array(
                    new Assert\NotBlank(),
                ),
            ))
        ;
    }

    public function getName()
    {
        return 'btn_pagebundle_nodecontent';
    }
}
