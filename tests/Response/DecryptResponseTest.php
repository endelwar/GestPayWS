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
    protected $descriptResponse;
    protected $emptyResponseObject;
    protected $goodResponseString = '<GestPayCryptDecrypt xmlns=""><TransactionType>DECRYPT</TransactionType><TransactionResult>OK</TransactionResult><ShopTransactionID>1</ShopTransactionID><BankTransactionID>7</BankTransactionID><AuthorizationCode>0013R4</AuthorizationCode><Currency>242</Currency><Amount>0.10</Amount><Country>ITALIA</Country><CustomInfo>STORE_ID=1*P1*STORE_NAME=Negozio%2BAbc</CustomInfo><Buyer><BuyerName>Name Surname</BuyerName><BuyerEmail>name.surname@example.org</BuyerEmail></Buyer><TDLevel>HALF</TDLevel><ErrorCode>0</ErrorCode><ErrorDescription>Transazione correttamente effettuata</ErrorDescription><AlertCode/><AlertDescription/><VbVRisp/><VbVBuyer/><VbVFlag/><TransactionKey/></GestPayCryptDecrypt>';
    private $goodResponseObject;
    protected $badResponseString1142 = '<GestPayCryptDecrypt xmlns=""><TransactionType>DECRYPT</TransactionType><TransactionResult>KO</TransactionResult><ErrorCode>1142</ErrorCode><ErrorDescription>Chiamata non accettata: indirizzo IP non valido</ErrorDescription></GestPayCryptDecrypt>';
    protected $badResponseString9999 = '<GestPayCryptDecrypt xmlns=""><TransactionType>DECRYPT</TransactionType><TransactionResult>KO</TransactionResult><ErrorCode>9999</ErrorCode><ErrorDescription>Errore di Sistema</ErrorDescription></GestPayCryptDecrypt>';

    protected $validData = array(
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
        'CustomInfo' => 'STORE_ID=1*P1*STORE_NAME=Negozio+Abc'
    );

    public function setUp()
    {
        $soapResponse = new \stdClass();
        $soapResponse->DecryptResult = new \stdClass();
        $soapResponse->DecryptResult->any = '';
        $this->emptyResponseObject = $soapResponse;

        $goodResponseObject = clone $this->emptyResponseObject;
        $goodResponseObject->DecryptResult->any = $this->goodResponseString;
        $this->goodResponseObject = $goodResponseObject;

        $this->descriptResponse = new DecryptResponse($goodResponseObject);
    }

    public function testToArray()
    {
        $this->assertArraySubset($this->validData, $this->descriptResponse->toArray());
    }

    public function testGetCustomInfoToArray()
    {
        $expect = array(
            'STORE_ID' => 1,
            'STORE_NAME' => 'Negozio Abc'
        );
        $this->assertEquals($expect, $this->descriptResponse->getCustomInfoToArray());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetException()
    {
        $this->descriptResponse->set('iDontExists', 'abc123');
    }

    public function testIsset()
    {
        $this->assertTrue(isset($this->descriptResponse->TransactionType));
    }

    public function testMagicSetter()
    {
        $this->descriptResponse->AuthorizationCode = 'ABC123';
        $this->assertEquals('ABC123', $this->descriptResponse->get('AuthorizationCode'));
    }

    /* *** testing ArrayAccess *** */
    public function testOffsetSet()
    {
        $decryptResponse = new DecryptResponse($this->goodResponseObject);
        $decryptResponse->offsetSet('AuthorizationCode', $this->validData['AuthorizationCode']);
        $this->assertEquals($this->descriptResponse->get('AuthorizationCode'), $this->validData['AuthorizationCode']);
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
}
