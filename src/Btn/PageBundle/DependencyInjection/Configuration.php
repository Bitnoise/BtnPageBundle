<?php

namespace Btn\PageBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('btn_page');

        $rootNode
            ->children()
                ->arrayNode('page')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')->cannotBeEmpty()->defaultValue('Btn\\PageBundle\\Entity\\Page')->end()
                    ->end()
                ->end()
                ->arrayNode('templates')
                    ->requiresAtLeastOneElement()
                    // ->beforeNormalization()
                    //     ->ifArray()
                    //     ->then(function ($v) {
                    //         foreach ($v as $key => $value) {
                    //             if (empty($value['name'])) {
                    //                 $v[$key]['name'] = $key;
                    //             }
                    //         }

                    //         return $v;
                    //     })
                    // ->end()
                    ->prototype('array')
                        ->children()
                            // ->scalarNode('name')->cannotBeEmpty()->defaultValue(null)->end()
                            ->scalarNode('template')->isRequired()->cannotBeEmpty()->example('BtnPageBundle:Example:template.html.twig')->end()
                            ->scalarNode('title')->isRequired()->cannotBeEmpty()->example('Example template')->end()
                            ->booleanNode('hide_content')->defaultValue(false)->end()
                            ->arrayNode('fields')
                                ->requiresAtLeastOneElement()
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('type')->defaultValue('btn_wysiwyg')->cannotBeEmpty()->example('text')->end()
                                        ->scalarNode('label')->defaultValue(null)->end()
                                        ->booleanNode('mapped')->defaultValue(false)->end()
                                        ->variableNode('attr')->end()
                                        ->scalarNode('class')->end()
                                        ->variableNode('query_builder')->end()
                                        ->scalarNode('property')->end()
                                        ->booleanNode('required')->end()
                                        ->booleanNode('multiple')->end()
                                        ->booleanNode('expanded')->end()
                                        ->scalarNode('empty_value')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end()
        ;

        return $treeBuilder;
    }
}
