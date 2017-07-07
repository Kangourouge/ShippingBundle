<?php

namespace KRG\ShippingBundle\Model;

class UpsModel extends ShippingModel
{
    /**
     * TrackingResponse constructor.
     */
    public function __construct($shipment, $number)
    {
        $this->number = $number;
        $this->reference = null;
        $this->status = $shipment->CurrentStatus->Description;
        $this->origin = self::formatAddress($shipment->Shipper->Address);
        $this->destination = self::formatAddress($shipment->ShipTo->Address);
        $this->pieces = $shipment->NumberOfPieces;
        $this->shipper = null;
        $this->consignee = null;
        $this->delivered = $shipment->CurrentStatus->Code === '011';
        $this->shippedAt = null;
        $this->shipmentEvents = array();

        foreach($shipment->Activity as $activity) {
            $this->shipmentEvents[] = array(
                'date'        => \DateTime::createFromFormat('Ymdhis', sprintf('%s%s', $activity->Date, $activity->Time)),
                'description' => $activity->Description,
                'signatory'   => null,
                'area'        => self::formatAddress($activity->ActivityLocation->Address),
                'pieces'      => null,
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
            $address->AddressLine1 ?? '',
            $address->City ?? '',
            $address->CountryCode ?? ''
        ));
    }
}
