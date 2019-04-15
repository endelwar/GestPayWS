<?php

/*
 * This file is part of the GestPayWS library.
 *
 * (c) Manuel Dalla Lana <endelwar@aregar.it>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EndelWar\GestPayWS\Parameter\Validator;

class DefaultValidator implements ParameterValidatorInterface
{
    private $invalidCharsFlattened;

    /** @see https://api.gestpay.it/#encrypt */
    private static $invalidChars = [
        '&',
        ' ',
        '§', //need also to be added programmatically, because UTF-8
        '(',
        ')',
        '*P1*',
        '*',
        '<',
        '>',
        ',',
        ';',
        ':',
        '//',
        '/*',
        '/',
        '[',
        ']',
        '?',
        '=',
        '--',
        '%',
        '~',
    ];

    public function __construct()
    {
        self::$invalidChars[22] = chr(167); //§ ascii char
    }

    /**
     * @param string $parameter
     *
     * @return bool
     */
    public function isValid($parameter)
    {
        $pregResult = preg_match_all('#' . $this->getInvalidCharsFlattened() . '#', $parameter, $matches);

        return $pregResult === 0;
    }

    /**
     * @param string $parameter
     *
     * @return array
     */
    public function getInvalidChars($parameter)
    {
        preg_match_all('#' . $this->getInvalidCharsFlattened() . '#', $parameter, $matches);
        if (count($matches[0]) > 0) {
            return array_unique($matches[0]);
        }

        return [];
    }

    private function getInvalidCharsFlattened()
    {
        if (null === $this->invalidCharsFlattened) {
            $invalidCharsQuoted = array_map([$this, 'invalidCharsCallback'], self::$invalidChars);
            $this->invalidCharsFlattened = implode('|', $invalidCharsQuoted);
        }

        return $this->invalidCharsFlattened;
    }

    private function invalidCharsCallback($invalidChar)
    {
        return preg_quote($invalidChar, '#');
    }

    public function getInvalidCharsArray()
    {
        return self::$invalidChars;
    }
}
