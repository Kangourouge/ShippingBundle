<?php

namespace KRG\ShippingBundle\Transport;

use KRG\ShippingBundle\Model\ShippingModelInterface;

interface TransportInterface
{
    /**
     * @param $number
     *
     * @return ShippingModelInterface
     */
    public function get($number);

    /**
     * @param $reference
     * @param $accountNumber
     *
     * @return ShippingModelInterface
     */
    public function find($reference, $accountNumber = null);
}
