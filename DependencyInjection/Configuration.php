<?php

namespace Beryllium\Icelus\DependencyInjection;

use Beryllium\Icelus\ImageService;
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
        $treeBuilder = new TreeBuilder('icelus');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('prefix')->defaultValue(ImageService::DEFAULT_PREFIX)->end()
                ->scalarNode('output_dir')->defaultNull()->end()
            ->end();

        return $treeBuilder;
    }
}
