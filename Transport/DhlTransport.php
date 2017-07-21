<?php

namespace KRG\ShippingBundle\Transport;

use KRG\ShippingBundle\Model\DhlModel;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\DomCrawler\Crawler;

class DhlTransport implements TransportInterface
{
    /**
     * @var TwigEngine
     */
    private $twig;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $siteId;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $accountNumber;

    /**
     * Dhl constructor.
     *
     * @param TwigEngine $twig
     * @param string $url
     * @param string $siteId
     * @param string $password
     * @param string $accountNumber
     */
    public function __construct(TwigEngine $twig, $url, $siteId, $password, $accountNumber)
    {
        $this->twig = $twig;
        $this->url = $url;
        $this->siteId = $siteId;
        $this->password = $password;
        $this->accountNumber = $accountNumber;
    }

    public function get($number)
    {
        $xml = $this->twig->render('KRGShippingBundle:Dhl:known.tracking.request.xml.twig', array(
            'trackingNumber'   => $number,
            'siteId'           => $this->siteId,
            'password'         => $this->password,
            'messageTime'      => new \DateTime,
            'messageReference' => md5($number),
        ));
        $response = $this->call($xml);

        $crawler = new Crawler($response);
        $awbInfo = $crawler->filterXPath('//req:TrackingResponse//AWBInfo');

        if ($awbInfo->count() === 0 || trim($awbInfo->filterXPath('//Status')->text()) !== 'success') {
            return false;
        }

        return new DhlModel($awbInfo);
    }

    public function find($reference, $accountNumber = null)
    {
        $accountNumber = $accountNumber ?? $this->accountNumber;

        $xml = $this->twig->render('KRGShippingBundle:Dhl:unknown.tracking.request.xml.twig', array(
            'trackingReference' => $reference,
            'siteId'            => $this->siteId,
            'password'          => $this->password,
            'accountNumber'     => $accountNumber,
            'messageTime'       => new \DateTime,
            'messageReference'  => md5(date('c')),
        ));

        $response = $this->call($xml);

        return $this->getMapping($response);
    }

    private function getMapping($response)
    {
        $crawler = new Crawler($response);
        $nodes = $crawler->filterXPath('//req:TrackingResponse//AWBInfo');

        if ($nodes->count() === 0) {
            return array();
        }

        $shipmentInfos = array();
        foreach ($nodes as $node) {
            $awbInfo = new Crawler($node);
            if (trim($awbInfo->filterXPath('//Status')->text()) === 'success') {
                $shipmentInfo = new ShipmentInfo($awbInfo);
                if ($shipmentInfo->getTrackingNumber() !== null) {
                    $shipmentInfos[$shipmentInfo->getTrackingNumber()] = $shipmentInfo;
                }
            }
        }

        return array_filter($shipmentInfos);
    }

    private function call($xml)
    {
        if (!$curl = curl_init()) {
            throw new \Exception('could not initialize curl');
        }
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);
        if (strlen($error = curl_error($curl)) > 0) {
            curl_close($curl);

            return false;
        }
        curl_close($curl);

        return $result;
    }
}
