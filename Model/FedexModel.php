<?php

namespace KRG\ShippingBundle\Model;

class FedexModel extends ShippingModel
{
    /**
     * FedexModel constructor.
     *
     * @param $response
     */
    public function __construct($response)
    {
        $trackDetails = $response->TrackDetails;

        $this->number = isset($trackDetails->TrackingNumber) ? $trackDetails->TrackingNumber : null;
        $this->status = isset($trackDetails->StatusDescription) ? $trackDetails->StatusDescription : null;
        $this->origin = isset($trackDetails->OriginLocationAddress) ? self::formatAddress($trackDetails->OriginLocationAddress) : null;
        $this->destination = isset($trackDetails->DestinationAddress) ? self::formatAddress($trackDetails->DestinationAddress) : null;
        $this->weight = isset($trackDetails->PackageWeight->Value) ? round($trackDetails->PackageWeight->Value * 0.45359237, 2) : null;
        $this->pieces = isset($trackDetails->PackageCount) ? $trackDetails->PackageCount : null;
        $this->shipper = isset($trackDetails->ShipperAddress) ? self::formatAddress($trackDetails->ShipperAddress) : null;
        $this->delivered = isset($trackDetails->StatusCode) ? $trackDetails->StatusCode === 'DL' : false;

        if (isset($trackDetails->ActualDeliveryTimestamp)) {
            $this->deliveredAt = \DateTime::createFromFormat('U', strtotime($trackDetails->ActualDeliveryTimestamp));
        }

        $this->shipmentEvents = array();
        if (isset($trackDetails->Events)) {
            foreach($trackDetails->Events as $event) {
                $this->shipmentEvents[] = array(
                    'date'        => \DateTime::createFromFormat('U', strtotime($event->Timestamp)),
                    'description' => $event->EventDescription,
                    'area'        => self::formatAddress($event->Address),
                );
            }
        }

        $shipmentEvent = end($this->shipmentEvents);
        if ($shipmentEvent) {
            $this->shippedAt = $shipmentEvent['date'];
        }
    }

    private static function formatAddress($address)
    {
        return trim(sprintf('%s %s %s',
            $address->City ?? '',
            $address->StateOrProvinceCode ?? '',
            $address->CountryCode ?? ''
        ));
    }
}
