<?php

namespace Btn\PageBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

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
                            ->scalarNode('template')
                                ->isRequired()
                                ->cannotBeEmpty()
                                ->example('BtnPageBundle:Example:template.html.twig')
                            ->end()
                            ->scalarNode('title')->isRequired()->cannotBeEmpty()->example('Example template')->end()
                            ->booleanNode('hide_content')->defaultValue(false)->end()
                            ->arrayNode('fields')
                                ->requiresAtLeastOneElement()
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('type')
                                            ->defaultValue('btn_wysiwyg')
                                            ->cannotBeEmpty()
                                            ->example('text')
                                        ->end()
                                        ->scalarNode('label')->defaultValue(null)->end()
                                        ->booleanNode('mapped')->defaultValue(false)->end()
                                        ->variableNode('attr')->end()
                                        ->scalarNode('class')->end()
                                        ->scalarNode('group_by')->end()
                                        ->variableNode('query_builder')->end()
                                        ->scalarNode('property')->end()
                                        ->booleanNode('required')->end()
                                        ->booleanNode('multiple')->end()
                                        ->booleanNode('expanded')->end()
                                        ->scalarNode('placeholder')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end()
        ;

        $this->addNodeContentProvider($rootNode);

        return $treeBuilder;
    }

    /**
     *
     */
    private function addNodeContentProvider(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('node_content_provider')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('page')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('enabled')->defaultTrue()->end()
                                ->scalarNode('route_name')->defaultValue('btn_page_page_show')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
