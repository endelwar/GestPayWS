<?php

/*
 * This file is part of the GestPayWS library.
 *
 * (c) Manuel Dalla Lana <endelwar@aregar.it>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EndelWar\GestPayWS\Response\Test;

use EndelWar\GestPayWS\Response\EncryptResponse;

class EncryptResponseTest extends \PHPUnit_Framework_TestCase
{
    public $goodSoapResponse;
    public $goodEncryptResonse;
    public $shopLogin = 'GESPAY60861';

    protected function setUp()
    {
        $this->goodSoapResponse = new \stdClass();
        $this->goodSoapResponse->EncryptResult = new \stdClass();
        $this->goodSoapResponse->EncryptResult->any = '<GestPayCryptDecrypt xmlns=""><TransactionType>ENCRYPT</TransactionType><TransactionResult>OK</TransactionResult><CryptDecryptString>7_uiFEF9drv60fbY7k6GUQzlPAEjKOJVz5l6oVUHpbZVmQkI7scV27zAGAsnQ*JPggwKUN6nseXie9tnL7YX85L1jm6IA9SRTd7Pe_cggddL1uJZHtWyElR_6Q5qjvPSfypShmKrcWncHNX1SO4PcrQY49jO7FDhF4y3aezyupy82aQs*Ov01*L*n3MFrcBS</CryptDecryptString><ErrorCode>0</ErrorCode><ErrorDescription/></GestPayCryptDecrypt>';
        $this->goodEncryptResonse = new EncryptResponse($this->goodSoapResponse);
    }

    public function testToArray()
    {
        $resultArray = $this->goodEncryptResonse->toArray();

        $expect = [
            'TransactionType' => 'ENCRYPT',
            'TransactionResult' => 'OK',
            'CryptDecryptString' => '7_uiFEF9drv60fbY7k6GUQzlPAEjKOJVz5l6oVUHpbZVmQkI7scV27zAGAsnQ*JPggwKUN6nseXie9tnL7YX85L1jm6IA9SRTd7Pe_cggddL1uJZHtWyElR_6Q5qjvPSfypShmKrcWncHNX1SO4PcrQY49jO7FDhF4y3aezyupy82aQs*Ov01*L*n3MFrcBS',
            'ErrorCode' => '0',
            'ErrorDescription' => '',
        ];

        $this->assertEquals($resultArray, $expect);
    }

    public function testToXML()
    {
        $validXML = <<<XML
<?xml version="1.0"?>
<GestPayCryptDecrypt>
  <TransactionType>ENCRYPT</TransactionType>
  <TransactionResult>OK</TransactionResult>
  <CryptDecryptString>7_uiFEF9drv60fbY7k6GUQzlPAEjKOJVz5l6oVUHpbZVmQkI7scV27zAGAsnQ*JPggwKUN6nseXie9tnL7YX85L1jm6IA9SRTd7Pe_cggddL1uJZHtWyElR_6Q5qjvPSfypShmKrcWncHNX1SO4PcrQY49jO7FDhF4y3aezyupy82aQs*Ov01*L*n3MFrcBS</CryptDecryptString>
  <ErrorCode>0</ErrorCode>
  <ErrorDescription></ErrorDescription>
</GestPayCryptDecrypt>

XML;

        $this->assertEquals($validXML, $this->goodEncryptResonse->toXML());
    }

    public function testGetUrl()
    {
        $resultUrl = $this->goodEncryptResonse->getPaymentPageUrl($this->shopLogin, 'test');
        $expect = 'https://testecomm.sella.it/pagam/pagam.aspx?a=GESPAY60861&b=7_uiFEF9drv60fbY7k6GUQzlPAEjKOJVz5l6oVUHpbZVmQkI7scV27zAGAsnQ*JPggwKUN6nseXie9tnL7YX85L1jm6IA9SRTd7Pe_cggddL1uJZHtWyElR_6Q5qjvPSfypShmKrcWncHNX1SO4PcrQY49jO7FDhF4y3aezyupy82aQs*Ov01*L*n3MFrcBS';

        $this->assertEquals($resultUrl, $expect);
    }

    public function testIsOK()
    {
        $this->assertTrue($this->goodEncryptResonse->isOK());
    }

    public function testIsNotOK()
    {
        $badSoapResponse = new \stdClass();
        $badSoapResponse->EncryptResult = new \stdClass();
        $badSoapResponse->EncryptResult->any = '<GestPayCryptDecrypt xmlns=""><TransactionType>ENCRYPT</TransactionType><TransactionResult>KO</TransactionResult><ErrorCode>1142</ErrorCode><ErrorDescription>Chiamata non accettata: indirizzo IP non valido</ErrorDescription></GestPayCryptDecrypt>';
        $badEncryptResonse = new EncryptResponse($badSoapResponse);

        $this->assertFalse($badEncryptResonse->isOK());
    }
}
