<?php

namespace KRG\ShippingBundle\Model;

class FedexModel extends ShippingModel
{
    /**
     * FedexModel constructor.
     *
     * @param $trackDetails
     */
    public function __construct($trackDetails)
    {
        $this->number = $trackDetails->TrackingNumber;
        $this->reference = null;
        $this->status = $trackDetails->StatusDescription;
        $this->origin = self::formatAddress($trackDetails->OriginLocationAddress);
        $this->destination = self::formatAddress($trackDetails->DestinationAddress);
        $this->pieces = $trackDetails->PackageCount;
        $this->shipper = self::formatAddress($trackDetails->ShipperAddress);;
        $this->consignee = null;
        $this->delivered = $trackDetails->StatusCode === 'DL';

        if (isset($trackDetails->ActualDeliveryTimestamp)) {
            $this->deliveredAt = \DateTime::createFromFormat('YYYY-MM-DDTHH:MM:SS-xx:xx', sprintf('%s', $trackDetails->ActualDeliveryTimestamp));
        }

        $this->shipmentEvents = array();
        foreach($trackDetails->Events as $event) {
            $this->shipmentEvents[] = array(
                'date'        => \DateTime::createFromFormat('YYYY-MM-DDTHH:MM:SS-xx:xx', sprintf('%s', $event->Timestamp)),
                'description' => $event->EventDescription,
                'area'        => self::formatAddress($event->Address),
            );
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
