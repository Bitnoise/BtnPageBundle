<?php

namespace Btn\PageBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;

/**
 *
 */
class BtnPageExtension extends Extension implements PrependExtensionInterface
{
    private $resourceDir = '/../Resources/config';

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('btn_page.page.class', $config['page']['class']);
        $container->setParameter('btn_page.templates', $config['templates']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.$this->resourceDir));
        $loader->load('services.yml');
        $loader->load('forms.yml');

        // get ckeditor_conf from config or load default options from ckeditor_conf.yml
        if (!empty($config['ckeditor_conf'])) {
            $container->setParameter('btn_page.ckeditor_conf', $config['ckeditor_conf']);
        } else {
            $loader->load('ckeditor_conf.yml');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        if ($container->hasExtension('btn_nodes')) {
            $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.$this->resourceDir));
            $loader->load('node-cp.yml');
        }
    }
}
