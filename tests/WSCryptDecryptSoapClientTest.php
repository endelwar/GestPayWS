<?php

/*
 * This file is part of the GestPayWS library.
 *
 * (c) Manuel Dalla Lana <endelwar@aregar.it>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EndelWar\GestPayWS\Test;

use EndelWar\GestPayWS\WSCryptDecryptSoapClient;

class WSCryptDecryptSoapClientTest extends \PHPUnit_Framework_TestCase
{
    /** @var WSCryptDecryptSoapClient */
    public $wsCryptDecryptSoapClientTest;
    /** @var WSCryptDecryptSoapClient */
    public $wsCryptDecryptSoapClientProduction;
    /** @var WSCryptDecryptSoapClient */
    public $wsCryptDecryptSoapClientTestWithCA;

    protected function setUp()
    {
        $this->wsCryptDecryptSoapClientTest = new WSCryptDecryptSoapClient(true);
        $this->wsCryptDecryptSoapClientProduction = new WSCryptDecryptSoapClient(false);
    }

    public function testGetSoapClientTestEnv()
    {
        $testClient = $this->wsCryptDecryptSoapClientTest->getSoapClient();
        $this->assertInstanceOf('soapClient', $testClient);
    }

    public function testGetSoapClientProductionEnv()
    {
        $productionClient = $this->wsCryptDecryptSoapClientProduction->getSoapClient();
        $this->assertInstanceOf('soapClient', $productionClient);
    }
}
