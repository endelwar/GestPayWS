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
    protected $wsdlUrl = [
        'test' => 'https://sandbox.gestpay.net/gestpay/GestPayWS/WsCryptDecrypt.asmx?wsdl',
        'production' => 'https://ecomms2s.sella.it/gestpay/GestPayWS/WsCryptDecrypt.asmx?wsdl',
    ];
    public $wsdlEnvironment;
    protected $streamContextOption = [];
    protected $certificatePeerName = [
        'test' => 'sandbox.gestpay.net',
        'production' => 'ecomms2s.sella.it',
    ];
    /** @var \soapClient $soapClient */
    protected $soapClient;
    public $version = '1.3.1';

    /**
     * WSCryptDecryptSoapClient constructor.
     * @param bool|false $testEnv enable the test environment
     */
    public function __construct($testEnv = false)
    {
        $soapClientDefaultOption = [
            'user_agent' => 'EndelWar-GestPayWS/' . $this->version . ' (+https://github.com/endelwar/GestPayWS)',
            'stream_context' => $this->getStreamContext($testEnv),
            'connection_timeout' => 3000,
        ];
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
        $soapClientTestOption = [
            'trace' => true,
            'cache_wsdl' => WSDL_CACHE_NONE,
        ];

        return $soapClientTestOption;
    }

    /**
     * @return array
     */
    private function setProductionEnvironment()
    {
        $this->wsdlEnvironment = 'production';

        return [];
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

        $this->streamContextOption['ssl']['crypto_method'] = STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT;

        $this->streamContextOption['ssl']['allow_self_signed'] = true;
        $this->streamContextOption['ssl']['verify_peer'] = true;
        $this->streamContextOption['ssl']['SNI_enabled'] = true;

        // Disable TLS compression to prevent CRIME attacks.
        $this->streamContextOption['ssl']['disable_compression'] = true;

        $this->streamContextOption['ssl']['peer_name'] = $host;
        $this->streamContextOption['ssl']['verify_peer_name'] = true;

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
