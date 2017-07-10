<?php

namespace KRG\ShippingBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class TransportRegistryPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $services = array();

        // Collect all transport_name.yml previously loaded in KRGShippingExtension
        foreach ($container->findTaggedServiceIds('krg.shipping') as $id => $config) {
            $alias = isset($config[0]['alias']) ? $config[0]['alias'] : $id;
            $services[$alias] = $id;
        }

        $definition = $container->getDefinition('krg.shipping.registry');
        $definition->addMethodCall('setTransports', array($services));

        $definition = $container->getDefinition('krg.shipping.form.type');
        $definition->addMethodCall('setTransports', array(array_keys($services)));
    }
}
