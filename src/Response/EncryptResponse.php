<?php

/*
 * This file is part of the GestPayWS library.
 *
 * (c) Manuel Dalla Lana <endelwar@aregar.it>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EndelWar\GestPayWS\Response;

/**
 * Class EncryptResponse
 *
 * @property string $TransactionType;
 * @property string $TransactionResult;
 * @property string $CryptDecryptString;
 * @property int $ErrorCode;
 * @property string $ErrorDescription;
 */
class EncryptResponse extends Response
{
    protected $paymentPageUrl = [
        'test' => 'https://testecomm.sella.it/pagam/pagam.aspx',
        'production' => 'https://ecomm.sella.it/pagam/pagam.aspx',
    ];
    protected $parametersName = [
        'TransactionType',
        'TransactionResult',
        'CryptDecryptString',
        'ErrorCode',
        'ErrorDescription',
    ];

    /**
     * @param \stdClass $soapResponse
     * @throws \Exception
     */
    public function __construct($soapResponse)
    {
        $xml = simplexml_load_string($soapResponse->EncryptResult->any);
        parent::__construct($xml);
    }

    /**
     * @param string $shopLogin
     * @param string $wsdlEnvironment
     * @return string
     */
    public function getPaymentPageUrl($shopLogin, $wsdlEnvironment)
    {
        return $this->paymentPageUrl[$wsdlEnvironment] . '?a=' . $shopLogin . '&b=' . $this->CryptDecryptString;
    }
}
