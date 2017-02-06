<?php

namespace Veta\HomeworkBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('veta_homework');
        $rootNode
            ->children()
                ->arrayNode('sidebar')
                    ->children()
                        ->integerNode('tags_limit')->defaultValue(10)->min(5)->max(20)->end()
                        ->integerNode('posts_limit')->defaultValue(10)->end()
                    ->end()
                ->end()
                ->arrayNode('homepage')
                    ->children()
                        ->integerNode('posts_per_page')->defaultValue(10)->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
