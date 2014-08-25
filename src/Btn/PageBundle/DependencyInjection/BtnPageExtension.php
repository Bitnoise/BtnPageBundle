<?php

namespace Btn\PageBundle\DependencyInjection;

use Btn\BaseBundle\DependencyInjection\AbstractExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class BtnPageExtension extends AbstractExtension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        parent::load($configs, $container);

        $config = $this->getProcessedConfig($container, $configs);

        $container->setParameter('btn_page.page.class', $config['page']['class']);
        $container->setParameter('btn_page.templates', $config['templates']);

        // get ckeditor_conf from config or load default options from ckeditor_conf.yml
        if (!empty($config['ckeditor_conf'])) {
            $container->setParameter('btn_page.ckeditor_conf', $config['ckeditor_conf']);
        } else {
            $loader = $this->getConfigLoader($container);
            $loader->load('ckeditor_conf.yml');
        }
    }
}
