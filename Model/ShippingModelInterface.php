<?php

namespace KRG\ShippingBundle\Model;

interface ShippingModelInterface
{
    /**
     * @return string
     */
    public function getNumber();

    /**
     * @return string
     */
    public function getReference();

    /**
     * @return string
     */
    public function getStatus();

    /**
     * @return string
     */
    public function getOrigin();

    /**
     * @return string
     */
    public function getDestination();

    /**
     * @return string
     */
    public function getPieces();

    /**
     * @return string
     */
    public function getShipper();

    /**
     * @return string
     */
    public function getConsignee();

    /**
     * @return array
     */
    public function getShipmentEvents();

    /**
     * @return boolean
     */
    public function isDelivered();

    /**
     * @return \DateTime
     */
    public function getDeliveredAt();

    /**
     * @return \DateTime
     */
    public function getShippedAt();

    /**
     * @return string
     */
    public function getSignatory();

    /**
     * @return array
     */
    public function getGroupedShipmentEvents();
}
