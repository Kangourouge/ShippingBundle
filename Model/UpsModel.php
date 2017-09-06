<?php

namespace KRG\ShippingBundle\Model;

class UpsModel extends ShippingModel
{
    /**
     * TrackingResponse constructor.
     *
     * @param $shipment
     * @param $reference
     */
    public function __construct($shipment, $reference)
    {
        $this->number = null;
        $this->reference = $reference;
        $this->status = isset($shipment->CurrentStatus) ? $shipment->CurrentStatus->Description : '';
        $this->origin = self::formatAddress($shipment->Shipper->Address);
        $this->destination = self::formatAddress($shipment->ShipTo->Address);
        $this->pieces = isset($shipment->NumberOfPieces) ? $shipment->NumberOfPieces : '';
        $this->shipper = null;
        $this->consignee = null;
        $this->delivered = isset($shipment->CurrentStatus) ? $shipment->CurrentStatus->Code === '011' : '';
        $this->shippedAt = null;
        $this->shipmentEvents = array();

        if (isset($shipment->Activity)) {
            foreach ($shipment->Activity as $activity) {
                $this->shipmentEvents[] = array(
                    'date'        => \DateTime::createFromFormat('Ymdhis', sprintf('%s%s', $activity->Date, $activity->Time)),
                    'description' => $activity->Description,
                    'signatory'   => null,
                    'area'        => self::formatAddress($activity->ActivityLocation->Address),
                    'pieces'      => null,
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
            $address->AddressLine1 ?? '',
            $address->City ?? '',
            $address->CountryCode ?? ''
        ));
    }
}
