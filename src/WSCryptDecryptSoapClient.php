<?php
/*
 * This file is part of the GestPayWS library.
 *
 * (c) Manuel Dalla Lana <endelwar@aregar.it>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EndelWar\GestPayWS;

class WSCryptDecryptSoapClient
{
    protected $wsdlUrl = array(
        'test' => 'https://testecomm.sella.it/gestpay/GestPayWS/WsCryptDecrypt.asmx?wsdl',
        'production' => 'https://ecomms2s.sella.it/gestpay/GestPayWS/WsCryptDecrypt.asmx?wsdl'
    );
    public $wsdlEnvironment;

    /**
     * As of 11 may 2015 production site supports the following ssl ciphers:
     * TLS_RSA_WITH_RC4_128_MD5 (0x4)   WEAK 	128
     * TLS_RSA_WITH_RC4_128_SHA (0x5)   WEAK 	128
     * TLS_RSA_WITH_3DES_EDE_CBC_SHA (0xa)      112
     *
     * The only one which is secure is TLS_RSA_WITH_3DES_EDE_CBC_SHA (called DES-CBC3-SHA in openssl)
     */
    protected $streamContextOption = array(
        'ssl' => array(
            'ciphers' => 'DES-CBC3-SHA:RC4-SHA:RC4-MD5',
            'SNI_enabled' => true,
            // TODO: something needs to be worked out on php < 5.6 on ssl cert verify
            //'verify_peer' => true,
            //'verify_depth' => 5,
        ),
    );
    protected $certificatePeerName = array(
        'test' => 'testecomm.sella.it',
        'production' => 'ecomms2s.sella.it'
    );
    protected $soapClient;

    /**
     * @param bool $testEnv enable the test environment
     */
    public function __construct($testEnv = false)
    {
        $soapClientDefaultOption = array(
            'user_agent' => 'EndelWar-GestPayWS/1.1 (+https://github.com/endelwar/GestPayWS)',
            'stream_context' => $this->getStreamContext($testEnv),
            'connection_timeout' => 3000,
        );
        if ($testEnv) {
            $soapClientEnvironmentOption = $this->setTestEnvironment();
        } else {
            $soapClientEnvironmentOption = $this->setProductionEnvironment();
        }
        $soapClientOption = array_merge($soapClientDefaultOption, $soapClientEnvironmentOption);
        $this->soapClient = new \soapClient($this->wsdlUrl[$this->wsdlEnvironment], $soapClientOption);
    }

    /**
     * @return array
     */
    private function setTestEnvironment()
    {
        $this->wsdlEnvironment = 'test';
        $soapClientTestOption = array(
            'trace' => true,
            'cache_wsdl' =>  WSDL_CACHE_NONE
        );

        return $soapClientTestOption;
    }

    /**
     * @return array
     */
    private function setProductionEnvironment()
    {
        $this->wsdlEnvironment = 'production';

        return array();
    }

    /**
     * @param bool $testEnv
     * @return resource
     */
    private function getStreamContext($testEnv = false)
    {
        if ($testEnv) {
            $host = $this->certificatePeerName['test'];
        } else {
            $host = $this->certificatePeerName['production'];
        }

        /**
         * Disable TLS compression to prevent CRIME attacks where supported (PHP 5.4.13 or later).
         */
        if (PHP_VERSION_ID >= 50413) {
            $this->streamContextOption['ssl']['disable_compression'] = true;
        }

        /**
         * CN_match was deprecated in favour of peer_name in PHP 5.6
         */
        if (PHP_VERSION_ID < 50600) {
            $this->streamContextOption['ssl']['CN_match'] = $host;
            $this->streamContextOption['ssl']['SNI_server_name'] = $host;
        } else {
            $this->streamContextOption['ssl']['peer_name'] = $host;
            $this->streamContextOption['ssl']['verify_peer_name'] = true;
        }

        return stream_context_create($this->streamContextOption);
    }

    /**
     * @return \soapClient
     */
    public function getSoapClient()
    {
        return $this->soapClient;
    }
}
