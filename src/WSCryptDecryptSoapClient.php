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
    protected $streamContextOption = array(
        'ssl' => array(
            'ciphers' => 'DHE-RSA-AES256-SHA:DHE-DSS-AES256-SHA:AES256-SHA:KRB5-DES-CBC3-MD5:KRB5-DES-CBC3-SHA:EDH-RSA-DES-CBC3-SHA:EDH-DSS-DES-CBC3-SHA:DES-CBC3-SHA:DES-CBC3-MD5:DHE-RSA-AES128-SHA:DHE-DSS-AES128-SHA:AES128-SHA:RC2-CBC-MD5:KRB5-RC4-MD5:KRB5-RC4-SHA:RC4-SHA:RC4-MD5:RC4-MD5:KRB5-DES-CBC-MD5:KRB5-DES-CBC-SHA:EDH-RSA-DES-CBC-SHA:EDH-DSS-DES-CBC-SHA:DES-CBC-SHA:DES-CBC-MD5:EXP-KRB5-RC2-CBC-MD5:EXP-KRB5-DES-CBC-MD5:EXP-KRB5-RC2-CBC-SHA:EXP-KRB5-DES-CBC-SHA:EXP-EDH-RSA-DES-CBC-SHA:EXP-EDH-DSS-DES-CBC-SHA:EXP-DES-CBC-SHA:EXP-RC2-CBC-MD5:EXP-RC2-CBC-MD5:EXP-KRB5-RC4-MD5:EXP-KRB5-RC4-SHA:EXP-RC4-MD5:EXP-RC4-MD5',
            'disable_compression' => true,
            'SNI_enabled' => true,
            'verify_peer' => true,
            'verify_depth' => 5,
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
            'user_agent' => 'EndelWar-GestPayWS/0.1 (+https://github.com/endelwar/GestPayWS)',
            'stream_context' => $this->getStreamContext($testEnv)
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
        $soapClientTestOption = array('trace' => true);

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
