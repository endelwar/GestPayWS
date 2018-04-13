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
     * @param mixed $currencyCode
     * @param mixed $expected
     */
    public function testCurrencyCode($currencyCode, $expected)
    {
        $this->assertEquals($expected, $currencyCode);
    }

    public function currencyProvider()
    {
        return [
            [Currency::USD, 1],
            [Currency::GBP, 2],
            [Currency::CHF, 3],
            [Currency::DKK, 7],
            [Currency::NOK, 8],
            [Currency::SEK, 9],
            [Currency::CAD, 12],
            [Currency::ITL, 18],
            [Currency::JPY, 71],
            [Currency::HKD, 103],
            [Currency::AUD, 109],
            [Currency::SGD, 124],
            [Currency::CNY, 144],
            [Currency::HUF, 153],
            [Currency::CZK, 223],
            [Currency::BRL, 234],
            [Currency::PLN, 237],
            [Currency::EUR, 242],
            [Currency::RUB, 244],
        ];
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
