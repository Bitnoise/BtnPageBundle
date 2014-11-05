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

        $container->setParameter('btn_page.node_content_provider.page', $config['node_content_provider']['page']);
    }
}
