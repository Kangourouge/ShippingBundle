<?php

namespace KRG\ShippingBundle\Fedex\TrackService;

class Request extends \FedEx\TrackService\Request
{
    protected static $wsdlFileName = 'TrackService_v14.wsdl';

    public static function getWsdlPath()
    {
        return realpath(__DIR__ . '/../../_wsdl/' . static::$wsdlFileName);
    }
}
