<?php

namespace KRG\ShippingBundle\Entity;

interface ShippingInterface
{
    public function getId();
    /**
     * Set transport
     *
     * @param string $transport
     * @return ShippingInterface
     */
    public function setTransport($transport);

    /**
     * Get transport
     *
     * @return string
     */
    public function getTransport();
    /**
     * Set reference
     *
     * @param string $reference
     * @return ShippingInterface
     */
    public function setReference($reference);
    /**
     * Get reference
     *
     * @return string
     */
    public function getReference();

    /**
     * Set number
     *
     * @param string $number
     * @return ShippingInterface
     */
    public function setNumber($number);

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber();

    /**
     * Set accountId
     *
     * @param string $accountId
     * @return ShippingInterface
     */
    public function setAccountId($accountId);

    /**
     * Get accountId
     *
     * @return string
     */
    public function getAccountId();
}
