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

use EndelWar\GestPayWS\Data\Currency;
use EndelWar\GestPayWS\Parameter\EncryptParameter;
use InvalidArgumentException;

class EncryptParameterTest extends \PHPUnit_Framework_TestCase
{
    private $encryptParam;
    private $validData = array(
        'shopLogin' => 'GESPAY60861',
        'uicCode' => Currency::EUR,
        'amount' => 1.23,
        'shopTransactionId' => 123
    );

    protected function setUp()
    {
        $this->encryptParam = new EncryptParameter();
    }

    public function testSet()
    {
        $this->encryptParam->set('shopLogin', 'GESPAY60861');
        $this->assertEquals($this->encryptParam->shopLogin, 'GESPAY60861');
    }

    public function testGet()
    {
        $this->encryptParam->set('shopLogin', 'GESPAY60861');
        $this->assertEquals($this->encryptParam->get('shopLogin'), 'GESPAY60861');
    }

    public function testGetNotExists()
    {
        $this->assertNull($this->encryptParam->get('iDontExist'));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetException()
    {
        $this->encryptParam->set('iDontExist', 'abcdef');
    }

    public function testSetCustomInfoStringOneValue()
    {
        $this->encryptParam->setCustomInfo('datum1=value1');
        $this->assertEquals($this->encryptParam->get('customInfo'), 'datum1=value1');
    }

    public function testSetCustomInfoStringTwoValue()
    {
        $this->encryptParam->setCustomInfo('datum1=value1*P1*datum2=value2');
        $this->assertEquals($this->encryptParam->get('customInfo'), 'datum1=value1*P1*datum2=value2');
    }

    public function testSetCustomInfoArrayOneValue()
    {
        $data = array(
            'datum1' => 'value1'
        );
        $this->encryptParam->setCustomInfo($data);
        $this->assertEquals($this->encryptParam->get('customInfo'), 'datum1=value1');
    }

    public function testSetCustomInfoArrayTwoValue()
    {
        $data = array(
            'datum1' => 'value1',
            'datum2' => 'value2',
        );
        $this->encryptParam->setCustomInfo($data);
        $this->assertEquals($this->encryptParam->get('customInfo'), 'datum1=value1*P1*datum2=value2');
    }

    public function testSetCustomInfoArrayOverLenght()
    {
        $data = array(
            'datum1' => '012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789OVERLENGHT'
        );
        $this->encryptParam->setCustomInfo($data);
        $this->assertEquals($this->encryptParam->get('customInfo'),
            'datum1=012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789');
    }

    public function testConstructFromArray()
    {
        $encryptParamArray = new EncryptParameter($this->validData);
        $this->assertEquals($encryptParamArray->get('shopLogin'), 'GESPAY60861');
        $this->assertEquals($encryptParamArray->get('uicCode'), Currency::EUR);
        $this->assertEquals($encryptParamArray->get('amount'), 1.23);
        $this->assertEquals($encryptParamArray->get('shopTransactionId'), 123);
        $this->assertEquals($encryptParamArray->shopLogin, 'GESPAY60861');
        $this->assertEquals($encryptParamArray->uicCode, Currency::EUR);
        $this->assertEquals($encryptParamArray->amount, 1.23);
        $this->assertEquals($encryptParamArray->shopTransactionId, 123);
    }

    public function testGetCustomInfoToArray()
    {
        $this->encryptParam->setCustomInfo($this->validData);
        $this->assertEquals($this->encryptParam->getCustomInfoToArray(), $this->validData);
    }

    public function testIfAllMandatoryParametersSet()
    {
        $encryptParamArray = new EncryptParameter($this->validData);
        $this->assertTrue($encryptParamArray->areAllMandatoryParametersSet());
    }

    public function testNotAllMandatoryParametersSet()
    {
        $data = array(
            'uicCode' => Currency::EUR,
            'shopTransactionId' => 123
        );
        $encryptParamArray = new EncryptParameter($data);
        $this->assertFalse($encryptParamArray->areAllMandatoryParametersSet());
    }

    /**
     * @dataProvider goodValuesProvider
     */
    public function testVerifyParameterValidity($value)
    {
        $this->assertTrue($this->encryptParam->verifyParameterValidity($value));
    }

    public function goodValuesProvider()
    {
        return array(
            array('GESPAY60861'),
            array(Currency::EUR),
            array(1.23),
            array(123)
        );
    }

    /**
     * @dataProvider badValuesProvider
     * @expectedException InvalidArgumentException
     */
    public function testVerifiParameterValidityException($value)
    {
        $this->encryptParam->verifyParameterValidity($value);
    }

    public function badValuesProvider()
    {
        return array(
            array('str§'),
            array('§str'),
            array('str*P1*str'),
            array('/*this is a comment*/'),
            array('str' . chr(167) . 'str'),
            array('str1&str2&str3')
        );
    }

    public function testGetMagicMethod()
    {
        $this->encryptParam->setCustomInfo($this->validData);
        $this->assertEquals($this->encryptParam->customInfoToArray, $this->validData);
    }

    public function testToArray()
    {
        $encryptParamArray = new EncryptParameter($this->validData);
        $this->assertArraySubset($this->validData, $encryptParamArray->toArray());
    }
}
