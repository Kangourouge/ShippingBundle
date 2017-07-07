<?php

namespace KRG\ShippingBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DhlCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('krg:shipping:track')
            ->setDescription('Shipping Tracking.')
            ->addOption('transport', null, InputOption::VALUE_REQUIRED)
            ->addArgument('number');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $api = $this
            ->getContainer()
            ->get('krg.shipping.registry')
            ->get($input->getOption('transport'));

        $shipment = $api->get($input->getArgument('number'));
    }
}
