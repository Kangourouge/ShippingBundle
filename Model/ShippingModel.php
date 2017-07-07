<?php

namespace KRG\ShippingBundle\Model;

abstract class ShippingModel implements ShippingModelInterface
{
    /**
     * @var string
     */
    protected $number;

    /**
     * @var string
     */
    protected $reference;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var string
     */
    protected $origin;

    /**
     * @var string
     */
    protected $destination;

    /**
     * @var string
     */
    protected $pieces;

    /**
     * @var string
     */
    protected $shipper;

    /**
     * @var string
     */
    protected $consignee;

    /**
     * @var string
     */
    protected $signatory;

    /**
     * @var bool
     */
    protected $delivered;

    /**
     * @var \DateTime
     */
    protected $deliveredAt;

    /**
     * @var \DateTime
     */
    protected $shippedAt;

    /**
     * @var array
     */
    protected $shipmentEvents;

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @return string
     */
    public function getPieces()
    {
        return $this->pieces;
    }

    /**
     * @return string
     */
    public function getShipper()
    {
        return $this->shipper;
    }

    /**
     * @return string
     */
    public function getConsignee()
    {
        return $this->consignee;
    }

    /**
     * @return array
     */
    public function getShipmentEvents()
    {
        return $this->shipmentEvents;
    }

    /**
     * @return boolean
     */
    public function isDelivered()
    {
        return $this->delivered;
    }

    /**
     * @return \DateTime
     */
    public function getDeliveredAt()
    {
        return $this->deliveredAt;
    }

    /**
     * @return \DateTime
     */
    public function getShippedAt()
    {
        return $this->shippedAt;
    }

    /**
     * @return string
     */
    public function getSignatory()
    {
        return $this->signatory;
    }

    public function getGroupedShipmentEvents()
    {
        $groupedShipmentEvents = array();
        foreach ($this->shipmentEvents as $shipmentEvent) {
            $date = $shipmentEvent['date']->format('D, M d, Y');
            if (!isset($groupedShipmentEvents[$date])) {
                $groupedShipmentEvents[$date] = array();
            }
            $groupedShipmentEvents[$date][] = $shipmentEvent;
        }

        return $groupedShipmentEvents;
    }
}
