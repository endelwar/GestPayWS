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
        'test' => 'https://sandbox.gestpay.net/gestpay/GestPayWS/WsCryptDecrypt.asmx?wsdl',
        'production' => 'https://ecomms2s.sella.it/gestpay/GestPayWS/WsCryptDecrypt.asmx?wsdl',
    );
    public $wsdlEnvironment;
    protected $streamContextOption = array();
    protected $certificatePeerName = array(
        'test' => 'sandbox.gestpay.net',
        'production' => 'ecomms2s.sella.it',
    );
    /** @var \soapClient $soapClient */
    protected $soapClient;
    public $version = '1.3.1';

    /**
     * WSCryptDecryptSoapClient constructor.
     * @param bool|false $testEnv enable the test environment
     * @param null $caFile path to Certification Authority bundle file
     */
    public function __construct($testEnv = false, $caFile = null)
    {
        $soapClientDefaultOption = array(
            'user_agent' => 'EndelWar-GestPayWS/' . $this->version . ' (+https://github.com/endelwar/GestPayWS)',
            'stream_context' => $this->getStreamContext($testEnv, $caFile),
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
            'cache_wsdl' => WSDL_CACHE_NONE,
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
     * @param string $caFile
     * @return resource
     */
    private function getStreamContext($testEnv = false, $caFile = null)
    {
        if ($testEnv) {
            $host = $this->certificatePeerName['test'];
        } else {
            $host = $this->certificatePeerName['production'];
        }

        if (PHP_VERSION_ID > 50607) {
            $this->streamContextOption['ssl']['crypto_method'] = STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT;
        } else {
            $this->streamContextOption['ssl']['crypto_method'] = STREAM_CRYPTO_METHOD_TLS_CLIENT;
        }

        $this->streamContextOption['ssl']['allow_self_signed'] = true;
        $this->streamContextOption['ssl']['verify_peer'] = true;
        $this->streamContextOption['ssl']['SNI_enabled'] = true;

        // Disable TLS compression to prevent CRIME attacks where supported (PHP 5.4.13 or later).
        if (PHP_VERSION_ID >= 50413) {
            $this->streamContextOption['ssl']['disable_compression'] = true;
        }

        if (PHP_VERSION_ID < 50600) {
            //CN_match was deprecated in favour of peer_name in PHP 5.6
            $this->streamContextOption['ssl']['CN_match'] = $host;
            $this->streamContextOption['ssl']['SNI_server_name'] = $host;
            // PHP 5.6 or greater will find the system cert by default. When < 5.6, use the system ca-certificates.
            if (null === $caFile) {
                $this->streamContextOption['ssl']['cafile'] = $this->getDefaultCABundle();
            } else {
                $this->streamContextOption['ssl']['cafile'] = $caFile;
            }
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

    /**
     * Returns the default cacert bundle for the current system.
     *
     * First, the openssl.cafile and curl.cainfo php.ini settings are checked.
     * If those settings are not configured, then the common locations for
     * bundles found on Red Hat, CentOS, Fedora, Ubuntu, Debian, FreeBSD, OS X
     * and Windows are checked. If any of these file locations are found on
     * disk, they will be utilized.
     *
     * Note: the result of this function is cached for subsequent calls.
     *
     * @throws \RuntimeException if no bundle can be found.
     * @return string
     *
     * @link https://github.com/guzzle/guzzle/blob/6.1.0/src/functions.php#L143
     */
    public function getDefaultCABundle()
    {
        $cafiles = array(
            // Red Hat, CentOS, Fedora (provided by the ca-certificates package)
            '/etc/pki/tls/certs/ca-bundle.crt',
            // Ubuntu, Debian (provided by the ca-certificates package)
            '/etc/ssl/certs/ca-certificates.crt',
            // FreeBSD (provided by the ca_root_nss package)
            '/usr/local/share/certs/ca-root-nss.crt',
            // OS X provided by homebrew (using the default path)
            '/usr/local/etc/openssl/cert.pem',
            // Google app engine
            '/etc/ca-certificates.crt',
            // Windows?
            'C:\\windows\\system32\\curl-ca-bundle.crt',
            'C:\\windows\\curl-ca-bundle.crt',
        );

        if ($ca = ini_get('openssl.cafile')) {
            return $ca;
        }
        if ($ca = ini_get('curl.cainfo')) {
            return $ca;
        }
        foreach ($cafiles as $filename) {
            if (file_exists($filename)) {
                return $filename;
            }
        }
        throw new \RuntimeException(<<< EOT
No system CA bundle could be found in any of the the common system locations.
PHP versions earlier than 5.6 are not properly configured to use the system's
CA bundle by default. Mozilla provides a commonly used CA bundle which can be
downloaded here (provided by the maintainer of cURL):
https://raw.githubusercontent.com/bagder/ca-bundle/master/ca-bundle.crt. Once
you have a CA bundle available on disk, you can set the 'openssl.cafile' PHP
ini setting to point to the path to the file. See http://curl.haxx.se/docs/sslcerts.html
for more information.
EOT
        );
    }
}

