<?php

namespace Lyssal\DoctrineExtraBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treebuilder = new TreeBuilder('lyssal_doctrine_extra');

        $treebuilder->getRootNode()
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('breadcrumbs')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('template')
                            ->cannotBeEmpty()
                            ->defaultValue('@LyssalDoctrineExtra/_breadcrumbs/default.html.twig')
                        ->end()
                        ->scalarNode('root')
                            ->defaultNull()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treebuilder;
    }
}
