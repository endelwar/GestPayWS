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
     */
    public function testLanguageCode($languageCode, $expected)
    {
        $this->assertEquals($expected, $languageCode);
    }

    public function languageProvider()
    {
        return array(
            array(Language::ITALIAN, 1),
            array(Language::ENGLISH, 2),
            array(Language::SPANISH, 3),
            array(Language::FRENCH, 4),
            array(Language::GERMAN, 5),
        );
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
