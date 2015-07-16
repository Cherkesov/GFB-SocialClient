<?php

namespace GFB\SocialClientBundle\DependencyInjection;

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
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('gfb_social_client');

        $rootNode
            ->children()
                ->arrayNode('vkontakte')
                ->children()
                    ->integerNode('client_id')->end()
                    ->scalarNode('client_secret')->end()
                    ->scalarNode('scope')->end()
                    ->scalarNode('api_version')->end()
                ->end()
                ->end()
                //
                ->arrayNode('facebook')
                ->children()
                    ->scalarNode('app_id')->end()
                    ->scalarNode('app_secret')->end()
                ->end()
                ->end()
                //
                ->arrayNode('instagram')
                ->children()
                    ->scalarNode('client_id')->end()
                    ->scalarNode('client_secret')->end()
                ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
