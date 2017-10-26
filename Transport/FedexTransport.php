<?php

namespace KRG\ShippingBundle\Transport;

use KRG\ShippingBundle\Fedex\TrackService\Request;
use KRG\ShippingBundle\Model\FedexModel;
use FedEx\TrackService;
use FedEx\TrackService\ComplexType\TrackRequest;

/*
 * FEDEX Test Tracking numbers
 *
 * https://www.fedex.com/templates/components/apps/wpor/secure/downloads/pdf/201707/FedEx_WebServices_TrackService_WSDLGuide_v2017.pdf

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
class FedexTransport implements TransportInterface
{
    private $key;
    private $password;
    private $accountNumber;
    private $meterNumber;

    /**
     * FedexTransport constructor.
     */
    public function __construct($key, $password, $accountNumber, $meterNumber)
    {
        $this->key = $key;
        $this->password = $password;
        $this->accountNumber = $accountNumber;
        $this->meterNumber = $meterNumber;
    }

    public function getWithXml($number)
    {
        $xml = "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:v14=\"http://fedex.com/ws/track/v14\">
	<soapenv:Header/>
	<soapenv:Body>
		<v14:TrackRequest>
			<v14:WebAuthenticationDetail>
				<v14:UserCredential>
                    <v14:Key>$this->key</v14:Key>
					<v14:Password>$this->password</v14:Password>
				</v14:UserCredential>
			</v14:WebAuthenticationDetail>
			<v14:ClientDetail>
				<v14:AccountNumber>$this->accountNumber</v14:AccountNumber>
				<v14:MeterNumber>$this->meterNumber</v14:MeterNumber>
			</v14:ClientDetail>
			<v14:TransactionDetail>
				<v14:CustomerTransactionId>Track By Number_v14</v14:CustomerTransactionId>
                <v14:Localization>
					<v14:LanguageCode>EN</v14:LanguageCode>
					<v14:LocaleCode>US</v14:LocaleCode>
				</v14:Localization>
			</v14:TransactionDetail>
			<v14:Version>
				<v14:ServiceId>trck</v14:ServiceId>
				<v14:Major>14</v14:Major>
				<v14:Intermediate>0</v14:Intermediate>
				<v14:Minor>0</v14:Minor>
			</v14:Version>
			<v14:SelectionDetails>
				<v14:PackageIdentifier>
					<v14:Type>TRACKING_NUMBER_OR_DOORTAG</v14:Type>
					<v14:Value>$number</v14:Value>
				</v14:PackageIdentifier>
				<v14:ShipmentAccountNumber/>
				<v14:SecureSpodAccount/>
				<v14:Destination>
					<v14:GeographicCoordinates>rates evertitque
					 aequora</v14:GeographicCoordinates>
                </v14:Destination>
            </v14:SelectionDetails>
			</v14:TrackRequest>
		</soapenv:Body>
	</soapenv:Envelope>";

        $url = 'https://wsbeta.fedex.com/web-services';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_PORT, 443);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $result = curl_exec($ch);
        curl_close($ch);

        echo $result;
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
            $response = (new TrackService\Request())->getTrackReply($trackRequest);

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

            if (isset($e->detail)) {
                echo $e->detail->cause.'<br>';
                echo $e->detail->code.'<br>';
                echo $e->detail->desc.'<br>';
            }

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
