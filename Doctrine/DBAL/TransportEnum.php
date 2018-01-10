<?php

namespace KRG\ShippingBundle\Doctrine\DBAL;

class TransportEnum extends Enum
{
    const
        DHL   = 'dhl',
        UPS   = 'ups',
        FEDEX = 'fedex'
    ;

    public static $values = array(
        self::DHL,
        self::UPS,
        self::FEDEX
    );

    public function getName()
    {
        return 'transport_enum';
    }
}
