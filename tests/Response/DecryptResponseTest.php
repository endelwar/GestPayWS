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

use EndelWar\GestPayWS\Data\Currency;
use EndelWar\GestPayWS\Response\DecryptResponse;

class DecryptResponseTest extends \PHPUnit_Framework_TestCase
{
    protected $decryptGoodResponse;
    protected $emptyResponseObject;
    protected $goodResponseString = '<GestPayCryptDecrypt xmlns=""><TransactionType>DECRYPT</TransactionType><TransactionResult>OK</TransactionResult><ShopTransactionID>1</ShopTransactionID><BankTransactionID>7</BankTransactionID><AuthorizationCode>0013R4</AuthorizationCode><Currency>242</Currency><Amount>0.10</Amount><Country>ITALIA</Country><CustomInfo>STORE_ID=1*P1*STORE_NAME=Negozio%2BAbc</CustomInfo><Buyer><BuyerName>Name Surname</BuyerName><BuyerEmail>name.surname@example.org</BuyerEmail></Buyer><TDLevel>HALF</TDLevel><ErrorCode>0</ErrorCode><ErrorDescription>Transazione correttamente effettuata</ErrorDescription><AlertCode/><AlertDescription/><VbVRisp/><VbVBuyer/><VbVFlag/><TransactionKey/></GestPayCryptDecrypt>';
    private $goodResponseObject;
    protected $badResponseString1142 = '<GestPayCryptDecrypt xmlns=""><TransactionType>DECRYPT</TransactionType><TransactionResult>KO</TransactionResult><ErrorCode>1142</ErrorCode><ErrorDescription>Chiamata non accettata: indirizzo IP non valido</ErrorDescription></GestPayCryptDecrypt>';
    protected $badResponseString9999 = '<GestPayCryptDecrypt xmlns=""><TransactionType>DECRYPT</TransactionType><TransactionResult>KO</TransactionResult><ErrorCode>9999</ErrorCode><ErrorDescription>Errore di Sistema</ErrorDescription></GestPayCryptDecrypt>';

    protected $validData = [
        'TransactionType' => 'DECRYPT',
        'TransactionResult' => 'OK',
        'ShopTransactionID' => '1',
        'BankTransactionID' => '7',
        'AuthorizationCode' => '0013R4',
        'Currency' => Currency::EUR,
        'Amount' => '0.10',
        'ErrorCode' => 0,
        'ErrorDescription' => 'Transazione correttamente effettuata',
        'Country' => 'ITALIA',
        'CustomInfo' => 'STORE_ID=1*P1*STORE_NAME=Negozio+Abc',
    ];

    public function setUp()
    {
        $soapResponse = new \stdClass();
        $soapResponse->DecryptResult = new \stdClass();
        $soapResponse->DecryptResult->any = '';
        $this->emptyResponseObject = $soapResponse;

        $goodResponseObject = clone $this->emptyResponseObject;
        $goodResponseObject->DecryptResult->any = $this->goodResponseString;
        $this->goodResponseObject = $goodResponseObject;

        $this->decryptGoodResponse = new DecryptResponse($goodResponseObject);
    }

    public function testToArray()
    {
        $this->assertArraySubset($this->validData, $this->decryptGoodResponse->toArray());
    }

    public function testGetCustomInfoToArray()
    {
        $expect = [
            'STORE_ID' => 1,
            'STORE_NAME' => 'Negozio Abc',
        ];
        $this->assertEquals($expect, $this->decryptGoodResponse->getCustomInfoToArray());
    }

    public function testToXML()
    {
        $validXML = <<<XML
<?xml version="1.0"?>
<GestPayCryptDecrypt>
  <TransactionType>DECRYPT</TransactionType>
  <TransactionResult>OK</TransactionResult>
  <ShopTransactionID>1</ShopTransactionID>
  <BankTransactionID>7</BankTransactionID>
  <AuthorizationCode>0013R4</AuthorizationCode>
  <Currency>242</Currency>
  <Amount>0.10</Amount>
  <Country>ITALIA</Country>
  <CustomInfo>STORE_ID=1*P1*STORE_NAME=Negozio+Abc</CustomInfo>
  <BuyerName>Name Surname</BuyerName>
  <BuyerEmail>name.surname@example.org</BuyerEmail>
  <TDLevel>HALF</TDLevel>
  <ErrorCode>0</ErrorCode>
  <ErrorDescription>Transazione correttamente effettuata</ErrorDescription>
  <AlertCode></AlertCode>
  <AlertDescription></AlertDescription>
  <VbVRisp></VbVRisp>
  <VbVBuyer></VbVBuyer>
  <VbVFlag></VbVFlag>
  <TransactionKey></TransactionKey>
</GestPayCryptDecrypt>

XML;

        $this->assertEquals($validXML, $this->decryptGoodResponse->toXML());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetException()
    {
        $this->decryptGoodResponse->set('iDontExists', 'abc123');
    }

    public function testIsset()
    {
        $this->assertTrue(isset($this->decryptGoodResponse->TransactionType));
    }

    public function testMagicSetter()
    {
        $this->decryptGoodResponse->AuthorizationCode = 'ABC123';
        $this->assertEquals('ABC123', $this->decryptGoodResponse->get('AuthorizationCode'));
    }

    public function testIsOK()
    {
        $this->assertTrue($this->decryptGoodResponse->isOK());
    }

    public function testIsNotOK1142()
    {
        $this->assertFalse($this->getBadResponse1142()->isOK());
    }

    public function testIsNotOK9999()
    {
        $this->assertFalse($this->getBadResponse9999()->isOK());
    }

    /* *** testing ArrayAccess *** */
    public function testOffsetSet()
    {
        $decryptResponse = new DecryptResponse($this->goodResponseObject);
        $decryptResponse->offsetSet('AuthorizationCode', $this->validData['AuthorizationCode']);
        $this->assertEquals($this->decryptGoodResponse->get('AuthorizationCode'),
            $this->validData['AuthorizationCode']);
    }

    public function testOffsetGet()
    {
        $decryptResponse = new DecryptResponse($this->goodResponseObject);
        $this->assertEquals($this->validData['AuthorizationCode'], $decryptResponse->offsetGet('AuthorizationCode'));
    }

    public function testOffsetUnset()
    {
        $decryptResponse = new DecryptResponse($this->goodResponseObject);
        $decryptResponse->offsetUnset('AuthorizationCode');
        $this->assertNull($decryptResponse->get('AuthorizationCode'));
    }

    public function testUnset()
    {
        $decryptResponse = new DecryptResponse($this->goodResponseObject);
        unset($decryptResponse->AuthorizationCode);
        $this->assertNull($decryptResponse->get('AuthorizationCode'));
    }

    public function testOffsetExists()
    {
        $decryptResponse = new DecryptResponse($this->goodResponseObject);
        $this->assertTrue($decryptResponse->offsetExists('AuthorizationCode'));
        $this->assertFalse($decryptResponse->offsetExists('iDontExist'));
    }

    /**
     * @return DecryptResponse
     */
    private function getBadResponse1142()
    {
        $badResponse1142Object = clone $this->emptyResponseObject;
        $badResponse1142Object->DecryptResult->any = $this->badResponseString1142;
        $this->goodResponseObject = $badResponse1142Object;

        return new DecryptResponse($badResponse1142Object);
    }

    /**
     * @return DecryptResponse
     */
    private function getBadResponse9999()
    {
        $badResponse9999Object = clone $this->emptyResponseObject;
        $badResponse9999Object->DecryptResult->any = $this->badResponseString9999;
        $this->goodResponseObject = $badResponse9999Object;

        return new DecryptResponse($badResponse9999Object);
    }
}
