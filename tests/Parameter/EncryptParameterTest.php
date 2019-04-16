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

class EncryptParameterTest extends \PHPUnit_Framework_TestCase
{
    private $encryptParam;
    private $validData = [
        'shopLogin' => 'GESPAY60861',
        'uicCode' => Currency::EUR,
        'amount' => 1.23,
        'shopTransactionId' => 123,
    ];

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->encryptParam = new EncryptParameter();
    }

    public function testSet()
    {
        $this->encryptParam->set('shopLogin', $this->validData['shopLogin']);
        $this->assertEquals($this->encryptParam->shopLogin, $this->validData['shopLogin']);
        $this->assertEquals($this->encryptParam['shopLogin'], $this->validData['shopLogin']);

        $this->encryptParam['uicCode'] = Currency::EUR;
        $this->assertEquals($this->encryptParam->uicCode, Currency::EUR);
        $this->assertEquals($this->encryptParam['uicCode'], Currency::EUR);

        $this->encryptParam->amount = $this->validData['amount'];
        $this->assertEquals($this->encryptParam->amount, $this->validData['amount']);
        $this->assertEquals($this->encryptParam['amount'], $this->validData['amount']);
    }

    public function testGet()
    {
        $this->encryptParam->set('shopLogin', $this->validData['shopLogin']);
        $this->assertEquals($this->encryptParam->get('shopLogin'), $this->validData['shopLogin']);
        $this->assertEquals($this->encryptParam['shopLogin'], $this->validData['shopLogin']);
        $this->assertEquals($this->encryptParam->offsetGet('shopLogin'), $this->validData['shopLogin']);
    }

    public function testGetNotExists()
    {
        $this->assertNull($this->encryptParam->get('iDontExist'));
        $this->assertNull($this->encryptParam['iDontExist']);
        $this->assertNull($this->encryptParam->offsetGet('iDontExist'));
    }

    /**
     * @expectedException \InvalidArgumentException
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
        $data = [
            'datum1' => 'value1',
        ];
        $this->encryptParam->setCustomInfo($data);
        $this->assertEquals($this->encryptParam->get('customInfo'), 'datum1=value1');
    }

    public function testSetCustomInfoArrayTwoValue()
    {
        $data = [
            'datum1' => 'value1',
            'datum2' => 'value2',
        ];
        $this->encryptParam->setCustomInfo($data);
        $this->assertEquals($this->encryptParam->get('customInfo'), 'datum1=value1*P1*datum2=value2');
    }

    public function testSetCustomInfoArrayOverLenght()
    {
        $data = [
            'datum1' => '012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789OVERLENGHT',
        ];
        $this->encryptParam->setCustomInfo($data);
        $this->assertEquals($this->encryptParam->get('customInfo'),
            'datum1=012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789');
    }

    public function testConstructFromArray()
    {
        $encryptParamArray = new EncryptParameter($this->validData);
        $this->assertEquals($encryptParamArray->get('shopLogin'), $this->validData['shopLogin']);
        $this->assertEquals($encryptParamArray->get('uicCode'), Currency::EUR);
        $this->assertEquals($encryptParamArray->get('amount'), $this->validData['amount']);
        $this->assertEquals($encryptParamArray->get('shopTransactionId'), $this->validData['shopTransactionId']);
        $this->assertEquals($encryptParamArray->shopLogin, $this->validData['shopLogin']);
        $this->assertEquals($encryptParamArray->uicCode, Currency::EUR);
        $this->assertEquals($encryptParamArray->amount, $this->validData['amount']);
        $this->assertEquals($encryptParamArray->shopTransactionId, $this->validData['shopTransactionId']);
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
        $data = [
            'uicCode' => Currency::EUR,
            'shopTransactionId' => $this->validData['shopTransactionId'],
        ];
        $encryptParamArray = new EncryptParameter($data);
        $this->assertFalse($encryptParamArray->areAllMandatoryParametersSet());
    }

    /**
     * @dataProvider goodValuesProvider
     *
     * @param string $key
     * @param mixed  $value
     */
    public function testVerifyParameterValidity($key, $value)
    {
        $this->assertTrue($this->encryptParam->verifyParameterValidity($key, $value));
    }

    public function goodValuesProvider()
    {
        return [
            ['shopLogin', $this->validData['shopLogin']],
            ['uicCode', Currency::EUR],
            ['amount', $this->validData['amount']],
            ['shopTransactionId', $this->validData['shopTransactionId']],
        ];
    }

    /**
     * @dataProvider badValuesProvider
     * @expectedException \InvalidArgumentException
     *
     * @param string $key
     * @param string $value
     */
    public function testVerifyParameterValidityException($key, $value)
    {
        $this->encryptParam->verifyParameterValidity($key, $value);
    }

    public function badValuesProvider()
    {
        return [
            ['shopLogin', 'str§'],
            ['shopLogin', '§str'],
            ['shopLogin', 'str*P1*str'],
            ['shopLogin', '/*this is a comment*/'],
            ['shopLogin', 'str' . chr(167) . 'str'],
            ['shopLogin', 'str1&str2&str3'],
        ];
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

    /* *** testing ArrayAccess *** */
    public function testOffsetSet()
    {
        $this->encryptParam->offsetSet('shopLogin', $this->validData['shopLogin']);
        $this->assertEquals($this->encryptParam->get('shopLogin'), $this->validData['shopLogin']);
    }

    public function testOffsetUnset()
    {
        $encryptParamArray = new EncryptParameter($this->validData);
        $encryptParamArray->offsetUnset('shopLogin');
        $this->assertNull($encryptParamArray->get('shopLogin'));
    }

    public function testUnset()
    {
        $encryptParamArray = new EncryptParameter($this->validData);
        unset($encryptParamArray->shopLogin);
        $this->assertNull($encryptParamArray->get('shopLogin'));
    }

    public function testOffsetExists()
    {
        $encryptParamArray = new EncryptParameter($this->validData);
        $this->assertTrue($encryptParamArray->offsetExists('shopLogin'));
        $this->assertFalse($encryptParamArray->offsetExists('iDontExist'));
    }
}
