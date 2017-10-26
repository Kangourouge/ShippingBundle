<?php

namespace KRG\ShippingBundle\Library;

class FedexCommon
{
    public static function  printSuccess($client, $response)
    {
        self::printReply($client, $response);
    }

    public static function  printReply($client, $response)
    {
        $highestSeverity = $response->HighestSeverity;
        if ($highestSeverity == "SUCCESS") {
            echo '<h2>The transaction was successful.</h2>';
        }
        if ($highestSeverity == "WARNING") {
            echo '<h2>The transaction returned a warning.</h2>';
        }
        if ($highestSeverity == "ERROR") {
            echo '<h2>The transaction returned an Error.</h2>';
        }
        if ($highestSeverity == "FAILURE") {
            echo '<h2>The transaction returned a Failure.</h2>';
        }
        echo "\n";
        self::printNotifications($response->Notifications);
        self::printRequestResponse($client, $response);
    }

    public static function  printRequestResponse($client)
    {
        echo '<h2>Request</h2>'."\n";
        echo '<pre>'.htmlspecialchars($client->__getLastRequest()).'</pre>';
        echo "\n";

        echo '<h2>Response</h2>'."\n";
        echo '<pre>'.htmlspecialchars($client->__getLastResponse()).'</pre>';
        echo "\n";
    }

    /**
     *  Print SOAP Fault
     */
    public static function  printFault($exception, $client)
    {
        echo '<h2>Fault</h2>'."<br>\n";
        echo "<b>Code:</b>{$exception->faultcode}<br>\n";
        echo "<b>String:</b>{$exception->faultstring}<br>\n";
        self::writeToLog($client);

        echo '<h2>Request</h2>'."\n";
        echo '<pre>'.htmlspecialchars($client->__getLastRequest()).'</pre>';
        echo "\n";
    }

    /**
     * SOAP request/response logging to a file
     */
    public static function  writeToLog($client)
    {

        /**
         * __DIR__ refers to the directory path of the library file.
         * This location is not relative based on Include/Require.
         */
        if (!$logfile = fopen(__DIR__.'/fedextransactions.log', "a")) {
            error_func("Cannot open ".__DIR__.'/fedextransactions.log'." file.\n", 0);
            exit(1);
        }
        fwrite($logfile,
            sprintf("\r%s:- %s",
                date("D M j G:i:s T Y"),
                $client->__getLastRequest()."\r\n".$client->__getLastResponse()."\r\n\r\n"));

    }

    /**
     * This section provides a convenient place to setup many commonly used variables
     * needed for the php sample code to function.
     */
    public static function  getProperty($var)
    {

        if ($var == 'key') {
            Return 'XXX';
        }
        if ($var == 'password') {
            Return 'XXX';
        }
        if ($var == 'parentkey') {
            Return 'XXX';
        }
        if ($var == 'parentpassword') {
            Return 'XXX';
        }
        if ($var == 'shipaccount') {
            Return 'XXX';
        }
        if ($var == 'billaccount') {
            Return 'XXX';
        }
        if ($var == 'dutyaccount') {
            Return 'XXX';
        }
        if ($var == 'freightaccount') {
            Return 'XXX';
        }
        if ($var == 'trackaccount') {
            Return 'XXX';
        }
        if ($var == 'dutiesaccount') {
            Return 'XXX';
        }
        if ($var == 'importeraccount') {
            Return 'XXX';
        }
        if ($var == 'brokeraccount') {
            Return 'XXX';
        }
        if ($var == 'distributionaccount') {
            Return 'XXX';
        }
        if ($var == 'locationid') {
            Return 'PLBA';
        }
        if ($var == 'printlabels') {
            Return true;
        }
        if ($var == 'printdocuments') {
            Return true;
        }
        if ($var == 'packagecount') {
            Return '4';
        }
        if ($var == 'validateaccount') {
            Return 'XXX';
        }
        if ($var == 'meter') {
            Return 'XXX';
        }

        if ($var == 'shiptimestamp') {
            Return mktime(10, 0, 0, date("m"), date("d") + 1, date("Y"));
        }

        if ($var == 'spodshipdate') {
            Return '2016-04-13';
        }
        if ($var == 'serviceshipdate') {
            Return '2013-04-26';
        }
        if ($var == 'shipdate') {
            Return '2016-04-21';
        }

        if ($var == 'readydate') {
            Return '2014-12-15T08:44:07';
        }
        //if($var == 'closedate') Return date("Y-m-d");
        if ($var == 'closedate') {
            Return '2016-04-18';
        }
        if ($var == 'pickupdate') {
            Return date("Y-m-d", mktime(8, 0, 0, date("m"), date("d") + 1, date("Y")));
        }
        if ($var == 'pickuptimestamp') {
            Return mktime(8, 0, 0, date("m"), date("d") + 1, date("Y"));
        }
        if ($var == 'pickuplocationid') {
            Return 'SQLA';
        }
        if ($var == 'pickupconfirmationnumber') {
            Return '1';
        }

        if ($var == 'dispatchdate') {
            Return date("Y-m-d", mktime(8, 0, 0, date("m"), date("d") + 1, date("Y")));
        }
        if ($var == 'dispatchlocationid') {
            Return 'NQAA';
        }
        if ($var == 'dispatchconfirmationnumber') {
            Return '4';
        }

        if ($var == 'tag_readytimestamp') {
            Return mktime(10, 0, 0, date("m"), date("d") + 1, date("Y"));
        }
        if ($var == 'tag_latesttimestamp') {
            Return mktime(20, 0, 0, date("m"), date("d") + 1, date("Y"));
        }

        if ($var == 'expirationdate') {
            Return date("Y-m-d", mktime(8, 0, 0, date("m"), date("d") + 15, date("Y")));
        }
        if ($var == 'begindate') {
            Return '2014-10-16';
        }
        if ($var == 'enddate') {
            Return '2014-10-16';
        }

        if ($var == 'trackingnumber') {
            Return 'XXX';
        }

        if ($var == 'hubid') {
            Return '5531';
        }

        if ($var == 'jobid') {
            Return 'XXX';
        }

        if ($var == 'searchlocationphonenumber') {
            Return '5555555555';
        }
        if ($var == 'customerreference') {
            Return '39589';
        }

        if ($var == 'shipper') {
            Return array(
                'Contact' => array(
                    'PersonName'  => 'Sender Name',
                    'CompanyName' => 'Sender Company Name',
                    'PhoneNumber' => '1234567890',
                ),
                'Address' => array(
                    'StreetLines'         => array('Addres \r  s Line 1'),
                    'City'                => 'Collierville',
                    'StateOrProvinceCode' => 'TN',
                    'PostalCode'          => '38017',
                    'CountryCode'         => 'US',
                    'Residential'         => 1,
                ),
            );
        }
        if ($var == 'recipient') {
            Return array(
                'Contact' => array(
                    'PersonName'  => 'Recipient Name',
                    'CompanyName' => 'Recipient Company Name',
                    'PhoneNumber' => '1234567890',
                ),
                'Address' => array(
                    'StreetLines'         => array('Address Line 1'),
                    'City'                => 'Herndon',
                    'StateOrProvinceCode' => 'VA',
                    'PostalCode'          => '20171',
                    'CountryCode'         => 'US',
                    'Residential'         => 1,
                ),
            );
        }

        if ($var == 'address1') {
            Return array(
                'StreetLines'         => array('10 Fed Ex Pkwy'),
                'City'                => 'Memphis',
                'StateOrProvinceCode' => 'TN',
                'PostalCode'          => '38115',
                'CountryCode'         => 'US',
            );
        }
        if ($var == 'address2') {
            Return array(
                'StreetLines'         => array('13450 Farmcrest Ct'),
                'City'                => 'Herndon',
                'StateOrProvinceCode' => 'VA',
                'PostalCode'          => '20171',
                'CountryCode'         => 'US',
            );
        }
        if ($var == 'searchlocationsaddress') {
            Return array(
                'StreetLines'         => array('240 Central Park S'),
                'City'                => 'Austin',
                'StateOrProvinceCode' => 'TX',
                'PostalCode'          => '78701',
                'CountryCode'         => 'US',
            );
        }

        if ($var == 'shippingchargespayment') {
            Return array(
                'PaymentType' => 'SENDER',
                'Payor'       => array(
                    'ResponsibleParty' => array(
                        'AccountNumber' => self::getProperty('billaccount'),
                        'Contact'       => null,
                        'Address'       => array('CountryCode' => 'US'),
                    ),
                ),
            );
        }
        if ($var == 'freightbilling') {
            Return array(
                'Contact' => array(
                    'ContactId'   => 'freight1',
                    'PersonName'  => 'Big Shipper',
                    'Title'       => 'Manager',
                    'CompanyName' => 'Freight Shipper Co',
                    'PhoneNumber' => '1234567890',
                ),
                'Address' => array(
                    'StreetLines'         => array(
                        '1202 Chalet Ln',
                        'Do Not Delete - Test Account',
                    ),
                    'City'                => 'Harrison',
                    'StateOrProvinceCode' => 'AR',
                    'PostalCode'          => '72601-6353',
                    'CountryCode'         => 'US',
                ),
            );
        }
    }

    public static function  setEndpoint($var)
    {
        if ($var == 'changeEndpoint') {
            Return false;
        }
        if ($var == 'endpoint') {
            Return 'XXX';
        }
    }

    public static function  printNotifications($notes)
    {
        foreach ($notes as $noteKey => $note) {
            if (is_string($note)) {
                echo $noteKey.': '.$note."<br>";
            } else {
                self::printNotifications($note);
            }
        }
        echo "<br>";
    }

    public static function  printError($client, $response)
    {
        self::printReply($client, $response);
    }

    public static function  trackDetails($details, $spacer)
    {
        foreach ($details as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $newSpacer = $spacer.'&nbsp;&nbsp;&nbsp;&nbsp;';
                echo '<tr><td>'.$spacer.$key.'</td><td>&nbsp;</td></tr>';
                self::trackDetails($value, $newSpacer);
            } elseif (empty($value)) {
                echo '<tr><td>'.$spacer.$key.'</td><td>'.$value.'</td></tr>';
            } else {
                echo '<tr><td>'.$spacer.$key.'</td><td>'.$value.'</td></tr>';
            }
        }
    }

}

