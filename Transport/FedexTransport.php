<?php

namespace KRG\ShippingBundle\Transport;

use FedEx\AbstractRequest;
use KRG\ShippingBundle\Model\FedexModel;
use FedEx\TrackService;
use FedEx\TrackService\ComplexType\TrackRequest;

/*
 * FEDEX Test Tracking numbers

449044304137821 = Shipment information sent to FedEx
149331877648230 = Tendered
020207021381215 = Picked Up
403934084723025 = Arrived at FedEx location
920241085725456 = At local FedEx facility
568838414941    = At destination sort facility
039813852990618 = Departed FedEx location
231300687629630 = On FedEx vehicle for delivery
797806677146    = International shipment release
377101283611590 = Customer not available or business closed
852426136339213 = Local Delivery Restriction
797615467620    = Incorrect Address
957794015041323 = Unable to Deliver
076288115212522 = Returned to Sender/Shipper
581190049992    = International Clearance delay
122816215025810 = Delivered
843119172384577 = Hold at Location
070358180009382 = Shipment Canceled
*/

/**
 * Class FedexTransport
 * @package KRG\ShippingBundle\Transport
 */
class FedexTransport extends AbstractRequest implements TransportInterface
{
    const PRODUCTION_URL = 'https://ws.fedex.com:443/web-services/track';
    const TESTING_URL = 'https://wsbeta.fedex.com:443/web-services/track';

    protected static $wsdlFileName = 'TrackService_v5.wsdl';

    /**
     * @var \SoapClient
     */
    private $client;
    private $key;
    private $password;
    private $accountNumber;
    private $meterNumber;

    /**
     * FedexTransport constructor.
     */
    public function __construct($key, $password, $accountNumber, $meterNumber)
    {
        parent::__construct();

        $this->client = $this->getSoapClient();
        $this->key = $key;
        $this->password = $password;
        $this->accountNumber = $accountNumber;
        $this->meterNumber = $meterNumber;
    }

    public function get($number)
    {
        $trackRequest = new TrackRequest();
        $trackRequest->setIncludeDetailedScans(true);

        // UserCredential
        $userCredential = new TrackService\ComplexType\WebAuthenticationCredential();
        $userCredential
            ->setKey($this->key)
            ->setPassword($this->password);

        // WebAuthentificationDetail
        $webAuthenticationDetail = new TrackService\ComplexType\WebAuthenticationDetail();
        $webAuthenticationDetail->setUserCredential($userCredential);
        $trackRequest->setWebAuthenticationDetail($webAuthenticationDetail);

        // Client detail
        $clientDetail = new TrackService\ComplexType\ClientDetail();
        $clientDetail
            ->setAccountNumber($this->accountNumber)
            ->setMeterNumber($this->meterNumber);
        $trackRequest->setClientDetail($clientDetail);

        // VersionId
        $versionId = new TrackService\ComplexType\VersionId();
        $versionId
            ->setServiceId('trck')
            ->setMajor(5)
            ->setIntermediate(0)
            ->setMinor(0);
        $trackRequest->setVersion($versionId);

        // PackageIdentifier
        $packageIdentifier = new TrackService\ComplexType\TrackPackageIdentifier();
        $packageIdentifier
            ->setType(TrackService\SimpleType\TrackIdentifierType::_TRACKING_NUMBER_OR_DOORTAG)
            ->setValue($number);
        $trackRequest->setPackageIdentifier($packageIdentifier);

        try {
            $response = $this->client->track($trackRequest->toArray());

            switch ($response->HighestSeverity) {
                case 'FAILURE':
                    throw new \Exception('FedEx was unable to process your transaction at this time due to a system failure. Please try again later.');
                    return null;

                case 'ERROR':
                    throw new \Exception('Information about an error that occurred while processing your transaction.');
                    return null;
            }
        } catch (\SoapFault $e) {
            echo $e->faultstring.'<br>';
            echo $e->detail->cause.'<br>';
            echo $e->detail->code.'<br>';
            echo $e->detail->desc.'<br>';

            return null;
        }

        return new FedexModel($response);
    }

    // TODO: find by reference FEDEX
    public function find($reference, $accountNumber = null)
    {
        $accountNumber = $accountNumber ?? $this->accountNumber;
    }
}
