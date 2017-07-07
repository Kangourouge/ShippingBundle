<?php

namespace KRG\ShippingBundle\Transport;

use KRG\ShippingBundle\Model\UpsModel;
use Ups\Tracking;

class UpsTransport implements TransportInterface
{
    /**
     * @var Tracking
     */
    private $ups;

    /**
     * UpsTransport constructor.
     */
    public function __construct($accessKey, $userId, $password)
    {
        // Turn last parameter to true for debugging
        $this->ups = new Tracking($accessKey, $userId, $password, true);
    }

    public function get($number)
    {
        try {
            $shipment = $this->ups->track($number);
        } catch (\Exception $e) {
            return null;
        }

        return new UpsModel($shipment, $number);
    }

    public function find($reference)
    {
        try {
            $shipment = $this->ups->trackByReference($reference);
        } catch (Exception $e) {
            return null;
        }

        return new UpsModel($shipment, $number);
    }
}
