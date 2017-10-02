<?php

namespace KRG\ShippingBundle\Transport;

use Symfony\Component\DependencyInjection\Container;

class TransportRegistry
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var array
     */
    private $transports;

    /**
     * @param Container $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * @param $name
     *
     * @return transportInterface
     */
    public function get($name)
    {
        if (!array_key_exists($name, $this->transports)) {
            throw new \InvalidArgumentException(sprintf('The transport "%s" is not registered with the service container.',
                $name));
        }

        if (is_string($this->transports[$name])) {
            $this->transports[$name] = $this->container->get($this->transports[$name]);
        }

        return $this->transports[$name];
    }

    /**
     * @return array
     */
    public function all()
    {
        foreach ($this->transports as $name => $transport) {
            $this->get($name);
        }

        return $this->transports;
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function has($name)
    {
        return isset($this->transports[$name]);
    }

    public function setTransports(array $transports)
    {
        $this->transports = $transports;
    }
}
