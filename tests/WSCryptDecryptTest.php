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

use EndelWar\GestPayWS\WSCryptDecrypt;
use EndelWar\GestPayWS\Data\Currency;
use EndelWar\GestPayWS\Parameter\EncryptParameter;
use EndelWar\GestPayWS\Parameter\DecryptParameter;

class WSCryptDecryptTest extends \PHPUnit_Framework_TestCase
{

    private $soapClientMock;
    private $mandatoryEncryptParameter = array(
        'shopLogin' => 'GESPAY60861',
        'uicCode' => Currency::EUR,
        'amount' => 1.23,
        'shopTransactionId' => 123
    );
    private $mandatoryDecryptParameter = array(
        'shopLogin' => 'GESPAY60861',
        'CryptedString' => "VBBeiJSy8qQc3_c7VaxCtfSCwr7Tz1o3czYGGHeX8QhfFMfpoWJtGLAnmSTtIt5MKHOP9y2ycqst6Ypiw000nW5uEo7usJh5KV5pmWQpkoNibgx5tneQPnPg9yOhk40ZfaEIC7p0905QRdiTPsX9UC0vzL4ypmsk0KI_AQaunyiiVKxcQ_zVjuHvBfB_i8coDATDiP2aspgkOiaoFwJEW6eVU3gHUXohAW0UU6Ag3HovCGC1F8803YwmwM4JSUsYoCZauxBSlVAxQHif0tEKfyxS8ev9hc1zgd2ewozCsBIUfoBkUyqAElfL4BDwjeEutZr5iMBBSgrRTDA5_oyoVkSUzBSVuQZI*94Hn8yAXtGKi1_uu6HK2kXQSE1R7W8f45r3Fj*COTJBFnWK8a93mZ0xmocrqzoUbEhQA5jh32ac9eU0gb2Wo676dfRozW31zmFuOSDPYOrTqsRGBJ5CxtL0HbIbv_l6nJlQH_yEyxjOfERxPk_5LYxPQHId7Ktj9Kr3wwPIuDRRJiby8c9Il8AoOMWCphtJn_fS75M3arWU3UjWO4tHa*yEoQlI7kzH"
    );

    protected function setUp()
    {
        $this->soapClientMock = $this->getMockFromWsdl(
            __DIR__ . '/WSCryptDecrypt.wsdl'
            //'WSCryptDecrypt'
        );

        $EncryptResult = new \stdClass();
        $EncryptResult->any = '<GestPayCryptDecrypt xmlns=""><TransactionType>ENCRYPT</TransactionType><TransactionResult>OK</TransactionResult><CryptDecryptString>0epeznjSImzEa48h8pOifDr3X7FKp*iwgpTqednfzs6TDeoL_*9u*YwL0de8Rho60bMGDoM7OnOFMAggUvA2fE1uxZaVG9OgTvDErP_Wk*jUSDcUXV7KogJ8*8yoAVaz*T8chCq_C_yOpZbYlVMw40WLZtrqDq80BDnEqNkeluJ5Pz_g76ZsmnJr0*v8xplC</CryptDecryptString><ErrorCode>0</ErrorCode><ErrorDescription/></GestPayCryptDecrypt>';
        $result = new \stdClass();
        $result->EncryptResult = $EncryptResult;

        $this->soapClientMock->expects($this->any())
            ->method('Encrypt')
            ->will($this->returnValue($result));

        $DecryptResult = new \stdClass();
        $DecryptResult->any = '<GestPayCryptDecrypt xmlns=""><TransactionType>DECRYPT</TransactionType><TransactionResult>OK</TransactionResult><ShopTransactionID>1</ShopTransactionID><BankTransactionID>7</BankTransactionID><AuthorizationCode>0013R4</AuthorizationCode><Currency>242</Currency><Amount>0.10</Amount><Country>ITALIA</Country><CustomInfo>STORE_ID=1*P1*STORE_NAME=Negozio%2BAbc</CustomInfo><Buyer><BuyerName>Manuel Test</BuyerName><BuyerEmail>manuel@dallalana.it</BuyerEmail></Buyer><TDLevel>HALF</TDLevel><ErrorCode>0</ErrorCode><ErrorDescription>Transazione correttamente effettuata</ErrorDescription><AlertCode/><AlertDescription/><VbVRisp/><VbVBuyer/><VbVFlag/><TransactionKey/></GestPayCryptDecrypt>';
        $result = new \stdClass();
        $result->DecryptResult = $DecryptResult;
        $this->soapClientMock->expects($this->any())
            ->method('Decrypt')
            ->will($this->returnValue($result));

    }

    public function testEncrypt()
    {
        $wsCryptParam = new EncryptParameter($this->mandatoryEncryptParameter);
        $wsCrypt = new WSCryptDecrypt($this->soapClientMock);

        $wsCryptResult = $wsCrypt->encrypt($wsCryptParam);
        $this->assertInstanceOf('EndelWar\GestPayWS\Response\EncryptResponse', $wsCryptResult);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testEncryptWithoutMandatoryParameter()
    {
        $wsCryptParam = new EncryptParameter(array());
        $wsCrypt = new WSCryptDecrypt($this->soapClientMock);

        $wsCryptResult = $wsCrypt->encrypt($wsCryptParam);
    }

    public function testDecrypt()
    {
        $wsCryptParam = new DecryptParameter($this->mandatoryDecryptParameter);
        $wsCrypt = new WSCryptDecrypt($this->soapClientMock);

        $wsCryptResult = $wsCrypt->decrypt($wsCryptParam);
        $this->assertInstanceOf('EndelWar\GestPayWS\Response\DecryptResponse', $wsCryptResult);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDecryptWithoutMandatoryParameter()
    {
        $wsDecryptParam = new DecryptParameter(array());
        $wsCrypt = new WSCryptDecrypt($this->soapClientMock);

        $wsDecryptResult = $wsCrypt->decrypt($wsDecryptParam);
    }
}
