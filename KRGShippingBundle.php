<?php

namespace KRG\ShippingBundle;

use KRG\ShippingBundle\DependencyInjection\Compiler\TransportRegistryPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class KRGShippingBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new TransportRegistryPass());
    }
}
