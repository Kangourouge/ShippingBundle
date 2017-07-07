<?php

namespace KRG\ShippingBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class TransportRegistryPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $services = array();

        foreach ($container->findTaggedServiceIds('krg.shipping') as $id => $config) {
            $alias = isset($config[0]['alias']) ? $config[0]['alias'] : $id;
            $services[$alias] = $id;
        }

        $definition = $container->getDefinition('krg.shipping.registry');
        $definition->replaceArgument(0, $services);

        $definition = $container->getDefinition('krg.shipping.form.type');
        $definition->replaceArgument(1, array_keys($services));
    }
}
