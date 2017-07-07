<?php

namespace KRG\ShippingBundle\Entity;

interface ShippingInterface
{
    public function getId();

    /**
     * Set transport
     *
     * @param string $transport
     *
     * @return Shipping
     */
    public function setTransport($transport);

    /**
     * Get transport
     *
     * @return string
     */
    public function getTransport();

    /**
     * Set number
     *
     * @param string $number
     *
     * @return Shipping
     */
    public function setNumber($number);

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber();

    /**
     * Set reference
     *
     * @param string reference
     *
     * @return Shipping
     */
    public function setReference($reference);

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference();
}
