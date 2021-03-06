<?php
namespace Dende\MultidatabaseBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('dende_multidatabase');

        $rootNode
            ->children()
            ->scalarNode('provider')->defaultValue('dende.tenant_provider')->end()
            ->scalarNode('connection')->defaultValue('tenant')->end()
            ->scalarNode('entity_manager')->defaultValue('tenant')->end()
            ->scalarNode('parameter_name')->defaultValue('tenant')->end()
            ->scalarNode('parameter_description')->defaultValue('Tenant\'s id')->end()
            ->arrayNode('fixtures')
                ->children()
                ->arrayNode("default")->prototype('scalar')->end()->end()
                ->arrayNode("tenant")->prototype('scalar')->end()->end()
                ->end()
            ->end()
            ->arrayNode('commands')
                ->prototype('scalar')->end()
            ->end()
        ->end()
        ;

        return $treeBuilder;
    }
}
