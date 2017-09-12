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
        $this->number = $shipment->ShipmentIdentificationNumber;
        $this->reference = $reference;
        $this->status = isset($shipment->CurrentStatus) ? $shipment->CurrentStatus->Description : '';
        $this->origin = self::formatAddress($shipment->Shipper->Address);
        $this->destination = self::formatAddress($shipment->ShipTo->Address);
        $this->pieces = isset($shipment->NumberOfPieces) ? $shipment->NumberOfPieces : '';
        $this->weight = isset($shipment->ShipmentWeight) ? $shipment->ShipmentWeight->Weight : '';
        $this->shipper = null;
        $this->consignee = null;
        $this->shippedAt = \DateTime::createFromFormat('Ymdhis', $shipment->PickupDate);
        $this->shipmentEvents = array();

        $this->deliveryDate = null;
        if (isset($shipment->ScheduledDeliveryDate)) {
            $this->deliveryDate = \DateTime::createFromFormat('Ymd', $shipment->ScheduledDeliveryDate);
        }
        if (isset($shipment->RescheduledDeliveryDate)) {
            $this->deliveryDate = \DateTime::createFromFormat('Ymd', $shipment->RescheduledDeliveryDate);
        }

        $this->delivered = false;
        if (isset($shipment->Package->Activity)) {
            foreach ($shipment->Package->Activity as $activity) {
                $this->shipmentEvents[] = array(
                    'date'        => \DateTime::createFromFormat('Ymdhis', sprintf('%s%s', $activity->Date, $activity->Time)),
                    'description' => $activity->Status->StatusType->Description,
                    'signatory'   => null,
                    'area'        => self::formatAddress($activity->ActivityLocation->Address),
                    'pieces'      => null,
                );

                if ($activity->Status->StatusType->Code === 'D') {
                    $this->delivered = true;
                }
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
