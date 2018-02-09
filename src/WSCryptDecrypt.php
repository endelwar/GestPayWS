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

use EndelWar\GestPayWS\Parameter\DecryptParameter;
use EndelWar\GestPayWS\Parameter\EncryptParameter;
use EndelWar\GestPayWS\Response\DecryptResponse;
use EndelWar\GestPayWS\Response\EncryptResponse;

/**
 * Class WSCryptDecrypt
 * @package EndelWar\GestPayWS
 */
class WSCryptDecrypt
{
    private $soapClient;

    /**
     * @param \SoapClient $soapClient
     */
    public function __construct(\SoapClient $soapClient)
    {
        $this->soapClient = $soapClient;
    }

    /**
     * @param EncryptParameter $parameters
     * @throws \Exception
     * @throws \InvalidArgumentException
     * @return EncryptResponse
     */
    public function encrypt(EncryptParameter $parameters)
    {
        if (!$parameters->areAllMandatoryParametersSet()) {
            throw new \InvalidArgumentException('Missing parameter');
        }
        $soapResponse = $this->soapClient->Encrypt($parameters);

        return new EncryptResponse($soapResponse);
    }

    /**
     * @param DecryptParameter $parameters
     * @throws \Exception
     * @throws \InvalidArgumentException
     * @return DecryptResponse
     */
    public function decrypt(DecryptParameter $parameters)
    {
        if (!$parameters->areAllMandatoryParametersSet()) {
            throw new \InvalidArgumentException('Missing parameter');
        }
        $soapResponse = $this->soapClient->Decrypt($parameters);

        return new DecryptResponse($soapResponse);
    }
}
