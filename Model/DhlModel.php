<?php

namespace KRG\ShippingBundle\Model;

use Symfony\Component\DomCrawler\Crawler;

class DhlModel extends ShippingModel
{
    const DATE_FORMAT = 'Y-m-d\TH:i:s';

    /**
     * TrackingResponse constructor.
     */
    public function __construct(Crawler $awbInfo)
    {
        $this->number = self::text($awbInfo, '//AWBNumber');
        $this->reference = self::text($awbInfo, '//ShipmentInfo//ShipperReference//ReferenceID');
        $this->status = self::text($awbInfo, '//Status//ActionStatus');
        $this->origin = self::text($awbInfo, '//ShipmentInfo//OriginServiceArea');
        $this->destination = self::text($awbInfo, '//ShipmentInfo//DestinationServiceArea');
        $this->pieces = self::text($awbInfo, '//ShipmentInfo//Pieces');
        $this->shipper = self::text($awbInfo, '//ShipmentInfo//Shipper');
        $this->consignee = self::text($awbInfo, '//ShipmentInfo//Consignee');
        $this->delivered = self::text($awbInfo, '//DlvyNotificationFlag') === 'Y';
        $this->shippedAt = \DateTime::createFromFormat(self::DATE_FORMAT, self::text($awbInfo, '//ShipmentInfo//ShipmentDate'));
        $this->shipmentEvents = array();

        $awbInfo->filterXPath('//ShipmentInfo//ShipmentEvent')->each(
            function (Crawler $node) {
                $date = self::text($node, '//Date');
                $time = self::text($node, '//Time');

                $eventCode = self::text($node, '//ServiceEvent//EventCode');
                $description = self::text($node, '//ServiceEvent//Description');
                $signatory = self::text($node, '//Signatory');
                $area = self::text($node, '//ServiceArea//Description');

                if ($eventCode === 'OK') {
                    $this->signatory = $signatory;
                    $this->deliveredAt = $date;
                    $description .= ' '.$signatory;
                }

                $this->shipmentEvents[] = array(
                    'date'        => \DateTime::createFromFormat(self::DATE_FORMAT, sprintf('%sT%s', $date, $time)),
                    'description' => $description,
                    'signatory'   => $signatory,
                    'area'        => $area,
                    'pieces'      => $this->pieces,
                );
                usort(
                    $this->shipmentEvents, function ($event1, $event2) {
                    return $event1['date'] < $event2['date'];
                }
                );
            }
        );
    }

    private static function text(Crawler $node, $xpath)
    {
        if (($_node = $node->filterXPath($xpath)) && $_node->count()) {
            return trim($_node->text());
        }

        return null;
    }
}
