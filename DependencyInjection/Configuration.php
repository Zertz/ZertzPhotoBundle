<?php

namespace Zertz\PhotoBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('zertz_photo');

        $rootNode
            ->children()
                ->scalarNode('directory')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('domain')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->integerNode('quality')
                    ->min(1)
                    ->max(100)
                    ->defaultValue(70)
                ->end()
                ->arrayNode('formats')
                    ->prototype('array')
                    ->children()
                        ->integerNode('quality')
                            ->min(1)
                            ->max(100)
                            ->defaultNull()
                            ->end()
                        ->arrayNode('size')
                            ->children()
                                ->integerNode('width')
                                    ->defaultValue(0)
                                ->end()
                                ->integerNode('height')
                                    ->defaultValue(0)
                                ->end()
                            ->end()
                        ->isRequired()
                        ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
