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
 * Class DecryptResponse
 */
class DecryptResponse extends Response
{
    protected $parametersName = [
        // Mandatory
        'TransactionType',
        'TransactionResult',
        'ShopTransactionID',
        'BankTransactionID',
        'AuthorizationCode',
        'Currency',
        'Amount',
        'ErrorCode',
        'ErrorDescription',

        // Optional
        'Country',
        'CustomInfo',
        'Buyer', // contains BuyerName and BuyerEmail
        'TDLevel',
        'AlertCode',
        'AlertDescription',
        'CVVPresent',
        'MaskedPAN',
        'PaymentMethod',
        'TOKEN',
        'ProductType',
        'TokenExpiryMonth',
        'TokenExpiryYear',
        'TransactionKey',
        'VbV',
        'VbVRisp',
        'VbVBuyer',
        'VbVFlag',
        'AVSResultCode',
        'AVSResultDescription',
        'RiskResponseCode',
        'RiskResponseDescription'
    ];
    protected $separator = '*P1*';

    /**
     * @param \stdClass $soapResponse
     * @throws \Exception
     */
    public function __construct($soapResponse)
    {
        $xml = simplexml_load_string($soapResponse->DecryptResult->any);
        if (isset($xml->CustomInfo)) {
            $xml->CustomInfo = urldecode($xml->CustomInfo);
        }
        parent::__construct($xml);
    }

    /**
     * @return array
     */
    public function getCustomInfoToArray()
    {
        $allinfo = explode($this->separator, $this->data['CustomInfo']);
        $customInfoArray = [];
        foreach ($allinfo as $singleInfo) {
            $tagvalue = explode('=', $singleInfo);
            $customInfoArray[$tagvalue[0]] = urldecode($tagvalue[1]);
        }

        return $customInfoArray;
    }
}
