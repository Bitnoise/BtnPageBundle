<?php

namespace Btn\PageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class NodeContentType extends AbstractType
{
    protected $data;

    public function __construct($data = array())
    {
        $this->data = $data;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('page', 'choice', array(
                'label'       => 'btn_page.page_node_content_provider.label',
                'empty_value' => 'btn_page.page_node_content_provider.empty_value',
                'choices'     => $this->data,
            ))
        ;
    }

    public function getName()
    {
        return 'btn_pagebundle_nodecontent';
    }
}
