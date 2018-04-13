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

use EndelWar\GestPayWS\Data\Language;

class LanguageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider languageProvider
     * @param mixed $languageCode
     * @param mixed $expected
     */
    public function testLanguageCode($languageCode, $expected)
    {
        $this->assertEquals($expected, $languageCode);
    }

    public function languageProvider()
    {
        return [
            [Language::ITALIAN, 1],
            [Language::ENGLISH, 2],
            [Language::SPANISH, 3],
            [Language::FRENCH, 4],
            [Language::GERMAN, 5],
        ];
    }

    public function testGetCode()
    {
        $languageClass = new Language();
        $this->assertEquals($languageClass->getCode('ITALIAN'), 1);
    }

    public function testGetCodeNotExists()
    {
        $languageClass = new Language();
        $this->assertEquals($languageClass->getCode('RUSSIAN'), false);
    }
}
