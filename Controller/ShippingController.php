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
     */
    public function showAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $className = $entityManager->getClassMetadata(ShippingInterface::class)->getName();
        /* @var $shipping ShippingInterface */
        $shipping = $entityManager->getRepository($className)->find($id);

        if (!$shipping) {
            throw new \Exception('Unable to find shipping');
        }

        /* @var $transport TransportInterface */
        $transport = $this->get('krg.shipping.registry')->get($shipping->getTransport());

        return $this->render(sprintf('KRGShippingBundle:Shipping:show.%s.html.twig', $shipping->getTransport()), array(
            'shipment' => $transport->get($shipping->getNumber()),
        ));
    }
}
