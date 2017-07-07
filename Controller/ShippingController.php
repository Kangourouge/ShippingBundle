<?php

namespace KRG\ShippingBundle\Controller;

use KRG\ShippingBundle\Entity\ShippingInterface;
use KRG\ShippingBundle\Transport\TransportInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ShippingController extends Controller
{
    /**
     * @Route("/shipping/{id}", name="shipping_show")
     * @Template()
     */
    public function showAction($id)
    {
        /* @var $shipping ShippingInterface */
        $shipping = $this->getDoctrine()->getManager()->getRepository($this->getParameter('krg.shipping.shipping_class'))->find($id);

        if (!$shipping) {
            throw new \Exception('Unable to find shipping');
        }

        /* @var $api TransportInterface */
        $transport = $this->get('krg.shipping.registry')->get($shipping->getTransport());

        $shipment = $transport->get($shipping->getNumber());

        return array(
            'shipment' => $shipment,
        );
    }
}
