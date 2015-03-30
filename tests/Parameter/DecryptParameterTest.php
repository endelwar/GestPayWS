<?php
/*
 * This file is part of the GestPayWS library.
 *
 * (c) Manuel Dalla Lana <endelwar@aregar.it>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EndelWar\GestPayWS\Parameter\Test;

use EndelWar\GestPayWS\Parameter\DecryptParameter;

class DecryptParameterTest extends \PHPUnit_Framework_TestCase
{
    public $decryptParam;
    public $shopLogin = 'GESPAY60861';
    public $CryptedString = "VBBeiJSy8qQc3_c7VaxCtfSCwr7Tz1o3czYGGHeX8QhfFMfpoWJtGLAnmSTtIt5MKHOP9y2ycqst6Ypiw000nW5uEo7usJh5KV5pmWQpkoNibgx5tneQPnPg9yOhk40ZfaEIC7p0905QRdiTPsX9UC0vzL4ypmsk0KI_AQaunyiiVKxcQ_zVjuHvBfB_i8coDATDiP2aspgkOiaoFwJEW6eVU3gHUXohAW0UU6Ag3HovCGC1F8803YwmwM4JSUsYoCZauxBSlVAxQHif0tEKfyxS8ev9hc1zgd2ewozCsBIUfoBkUyqAElfL4BDwjeEutZr5iMBBSgrRTDA5_oyoVkSUzBSVuQZI*94Hn8yAXtGKi1_uu6HK2kXQSE1R7W8f45r3Fj*COTJBFnWK8a93mZ0xmocrqzoUbEhQA5jh32ac9eU0gb2Wo676dfRozW31zmFuOSDPYOrTqsRGBJ5CxtL0HbIbv_l6nJlQH_yEyxjOfERxPk_5LYxPQHId7Ktj9Kr3wwPIuDRRJiby8c9Il8AoOMWCphtJn_fS75M3arWU3UjWO4tHa*yEoQlI7kzH";

    public function setUp()
    {
        $this->decryptParam = new DecryptParameter();
    }

    public function testSetGet()
    {
        $this->decryptParam->set('shopLogin', $this->shopLogin);
        $this->assertEquals($this->shopLogin, $this->decryptParam->shopLogin);
        $this->assertEquals($this->shopLogin, $this->decryptParam['shopLogin']);
        $this->assertEquals($this->shopLogin, $this->decryptParam->offsetGet('shopLogin'));

        $this->decryptParam->set('CryptedString', $this->CryptedString);
        $this->assertEquals($this->CryptedString, $this->decryptParam->CryptedString);
        $this->assertEquals($this->CryptedString, $this->decryptParam['CryptedString']);
        $this->assertEquals($this->CryptedString, $this->decryptParam->offsetGet('CryptedString'));
    }

    public function testGet()
    {
        $this->decryptParam->fromArray(
            array(
                'shopLogin' => $this->shopLogin,
                'CryptedString' => $this->CryptedString
            )
        );
        $this->assertEquals($this->CryptedString, $this->decryptParam->CryptedString);
        $this->assertEquals($this->shopLogin, $this->decryptParam->shopLogin);
    }

    public function testToArray()
    {
        $expect = array(
            'shopLogin' => $this->shopLogin,
            'CryptedString' => $this->CryptedString
        );
        $this->decryptParam->set('shopLogin', $this->shopLogin);
        $this->decryptParam->set('CryptedString', $this->CryptedString);
        $this->assertEquals($expect, $this->decryptParam->toArray());
    }

    public function testFromArray()
    {
        $expect = array(
            'shopLogin' => $this->shopLogin,
            'CryptedString' => $this->CryptedString
        );
        $decryptParamFromArray = new DecryptParameter();
        $decryptParamFromArray->fromArray($expect);
        $this->assertEquals($expect, $decryptParamFromArray->toArray());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidParameterName()
    {
        $this->decryptParam->set('iDontExist', 'abcdef');
    }
}
