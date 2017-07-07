<?php

namespace KRG\ShippingBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('krg_shipping');
        $rootNode
            ->children()
                ->scalarNode('shipping_class')->isRequired()->cannotBeEmpty()->end()
                ->arrayNode('transports')
                    ->children()
                        ->arrayNode('dhl')
                            ->children()
                                ->scalarNode('url')->end()
                                ->scalarNode('site_id')->end()
                                ->scalarNode('password')->end()
                                ->scalarNode('account_number')->end()
                            ->end()
                        ->end()
                        ->arrayNode('ups')
                            ->children()
                                ->scalarNode('access_key')->end()
                                ->scalarNode('user_id')->end()
                                ->scalarNode('password')->end()
                            ->end()
                        ->end()
                        ->arrayNode('fedex')
                            ->children()
                                ->scalarNode('key')->end()
                                ->scalarNode('password')->end()
                                ->scalarNode('account_number')->end()
                                ->scalarNode('meter_number')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
