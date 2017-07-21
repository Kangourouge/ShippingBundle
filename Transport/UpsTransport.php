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

     * @param $accessKey
     * @param $userId
     * @param $password
     */
    public function __construct($accessKey, $userId, $password)
    {
        // Turn last parameter to true for debugging
        $this->ups = new Tracking($accessKey, $userId, $password, true);
    }

    /**
     * @param $number
     * @return UpsModel|null
     */
    public function get($number)
    {
        try {
            $shipment = $this->ups->track($number);
        } catch (\Exception $e) {
            return null;
        }

        return new UpsModel($shipment, $number);
    }

    /**
     * @param $reference
     * @param null $accountNumber
     * @return UpsModel|null
     */
    public function find($reference, $accountNumber = null)
    {
        try {
            $shipment = $this->ups->trackByReference($reference);
        } catch (\Exception $e) {
            return null;
        }

        return new UpsModel($shipment, $reference);
    }
}
