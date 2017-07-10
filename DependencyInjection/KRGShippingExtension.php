<?php

namespace KRG\ShippingBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class KRGShippingExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('krg.shipping.shipping_class', $config['shipping_class']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        // Load transport_name.yml file only if it has been declared in config.yml
        foreach ($config['transports'] as $name => $config) {
            $loader->load(sprintf('%s.yml', $name));
            foreach ($config as $key => $value) {
                $container->setParameter(sprintf('krg.shipping.%s.%s', $name, $key), $value);
            }
        }
    }
}
