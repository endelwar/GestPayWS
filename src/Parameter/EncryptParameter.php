<?php
/*
 * This file is part of the GestPayWS library.
 *
 * (c) Manuel Dalla Lana <endelwar@aregar.it>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EndelWar\GestPayWS\Parameter;

use InvalidArgumentException;

/**
 * Class EncryptParameter
 * @package EndelWar\GestPayWS\Parameter
 *
 * @property string $shopLogin
 * @property int $uicCode;
 * @property float $amount;
 * @property string $shopTransactionId;
 * @property string $buyerName;
 * @property string $buyerEmail;
 * @property int $languageId;
 * @property string $customInfo;
 * @property string $requestToken;
 * @property string $ppSellerProtection;
 * @property string $shippingDetails;
 * @property string $paymentTypes;
 * @property string $paymentTypeDetail;
 */
class EncryptParameter extends Parameter
{
    protected $parametersName = array(
        // Mandatory parameters
        'shopLogin',
        'uicCode',
        'amount',
        'shopTransactionId',
        // Optional parameters
        'buyerName',
        'buyerEmail',
        'languageId',
        'customInfo',
        'requestToken',
        //'cardNumber', //deprecated
        //'expiryMonth', //deprecated
        //'expiryYear', //deprecated
        //'cvv', //deprecated

        /* to be implemented
        'ppSellerProtection',
        'shippingDetails',
        'paymentTypes',
        'paymentTypeDetail',
        'redFraudPrevention',
        'Red_CustomerInfo',
        'Red_ShippingInfo',
        'Red_BillingInfo',
        'Red_CustomerData',
        'Red_CustomInfo',
        'Red_Items',
        'Consel_MerchantPro',
        'Consel_CustomerInfo',
        'payPalBillingAgreementDescription'
        */
    );
    protected $mandatoryParameters = array(
        'shopLogin',
        'uicCode',
        'amount',
        'shopTransactionId',
    );
    protected $separator = "*P1*";
    private $customInfoArray = array();
    private $invalidChars = array(
        '&',
        ' ',
        '§', //need also to be added programmatically, because UTF-8
        '(',
        ')',
        '*',
        '<',
        '>',
        ',',
        ';',
        ':',
        '*P1*',
        '/',
        '[',
        ']',
        '?',
        '=',
        '--',
        '/*',
        '%',
        '//'
    );
    private $invalidCharsFlattened = '';

    public function __construct(array $parameters = array())
    {
        foreach ($this->parametersName as $parameterName) {
            $this->parameters[$parameterName] = null;
        }

        $this->invalidChars[] = chr(167); //§ ascii char

        if (!empty($parameters)) {
            $this->fromArray($parameters);
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        if (!in_array($key, $this->parametersName)) {
            throw new InvalidArgumentException(sprintf('%s is not a valid parameter name.', $key));
        }
        $this->verifyParameterValidity($value);
        $this->parameters[$key] = $value;
    }

    /**
     * @param mixed $customInfo string already encoded or array of key/value to be encoded
     */
    public function setCustomInfo($customInfo)
    {
        if (!is_array($customInfo)) {
            $this->parameters['customInfo'] = $customInfo;
        } else {
            //check string validity

            foreach ($customInfo as $key => $value) {
                $value = urlencode($value);
                $this->verifyParameterValidity($key);
                $this->verifyParameterValidity($value);

                if (strlen($value) > 300) {
                    $value = substr($value, 0, 300);
                }
                $customInfo[$key] = $value;
            }
            $this->customInfoArray = $customInfo;
            $this->parameters['customInfo'] = http_build_query($this->customInfoArray, '', $this->separator);
        }
    }

    /**
     * @return array
     */
    public function getCustomInfoToArray()
    {
        $allinfo = explode($this->separator, $this->customInfo);
        $customInfoArray = array();
        foreach ($allinfo as $singleInfo) {
            $tagvalue = explode("=", $singleInfo);
            $customInfoArray[$tagvalue[0]] = urldecode($tagvalue[1]);
        }

        return $customInfoArray;
    }

    /**
     * @return bool
     */
    public function areAllMandatoryParametersSet()
    {
        foreach ($this->mandatoryParameters as $param) {
            if (!isset($this->$param)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $value
     * @return bool
     */
    public function verifyParameterValidity($value)
    {
        if (strlen($this->invalidCharsFlattened) == 0) {
            $invalidCharsQuoted = array_map('preg_quote', $this->invalidChars);
            $this->invalidCharsFlattened = implode('|', $invalidCharsQuoted);
        }

        if (preg_match_all('#' . $this->invalidCharsFlattened . '#', $value, $matches)) {
            $invalidCharsMatched = '"' . implode('", "', $matches[0]) . '"';
            throw new InvalidArgumentException(
                'String ' . $value . ' contains invalid chars (i.e.: ' . $invalidCharsMatched . ').'
            );
        }

        return true;
    }
}
