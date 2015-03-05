<?php

namespace Beryllium\Icelus\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration.
 */
class Configuration implements ConfigurationInterface
{
    /**
    * {@inheritdoc}
    */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder;

        $rootNode = $treeBuilder->root('icelus');

        $rootNode
            ->children()
                ->scalarNode('prefix')->defaultValue('/_thumbs')->end()
                ->scalarNode('output_dir')->defaultNull()->end()
            ->end();

        return $treeBuilder;
    }
}
