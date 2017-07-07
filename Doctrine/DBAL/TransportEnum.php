<?php

namespace KRG\ShippingBundle\Doctrine\DBAL;

class TransportEnum extends EnumType
{
    const
        DHL   = 'dhl',
        UPS   = 'ups',
        FEDEX = 'fedex'
    ;

    public static $values = array(
        self::DHL   => self::DHL,
        self::UPS   => self::UPS,
        self::FEDEX => self::FEDEX,
    );

    public function getName()
    {
        return 'transport_enum';
    }
}
