<?php

/*
 * This file is part of the GestPayWS library.
 *
 * (c) Manuel Dalla Lana <endelwar@aregar.it>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EndelWar\GestPayWS\Data\Test;

use EndelWar\GestPayWS\Data\Currency;

class CurrencyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider currencyProvider
     */
    public function testCurrencyCode($currencyCode, $expected)
    {
        $this->assertEquals($expected, $currencyCode);
    }

    public function currencyProvider()
    {
        return array(
            array(Currency::USD, 1),
            array(Currency::GBP, 2),
            array(Currency::CHF, 3),
            array(Currency::DKK, 7),
            array(Currency::NOK, 8),
            array(Currency::SEK, 9),
            array(Currency::CAD, 12),
            array(Currency::ITL, 18),
            array(Currency::JPY, 71),
            array(Currency::HKD, 103),
            array(Currency::AUD, 109),
            array(Currency::SGD, 124),
            array(Currency::CNY, 144),
            array(Currency::HUF, 153),
            array(Currency::CZK, 223),
            array(Currency::BRL, 234),
            array(Currency::PLN, 237),
            array(Currency::EUR, 242),
            array(Currency::RUB, 244),
        );
    }

    public function testGetCode()
    {
        $languageClass = new Currency();
        $this->assertEquals($languageClass->getCode('EUR'), 242);
    }

    public function testGetCodeNotExists()
    {
        $languageClass = new Currency();
        $this->assertEquals($languageClass->getCode('CUP'), false); //Cuban Peso is not supported
    }
}
