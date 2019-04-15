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

class ApikeyValidator implements ParameterValidatorInterface
{
    /**
     * Apikey is a Base64 string with no defined lenght
     *
     * @param string|null $apikey
     *
     * @return bool
     */
    public function isValid($apikey)
    {
        if ($apikey === '' || $apikey === null) {
            return true;
        }

        if (!is_string($apikey)) {
            return false;
        }

        if (preg_match('/^[A-Za-z0-9+\/]+\={0,3}$/', $apikey, $matches) > 0) {
            return $apikey === $matches[0];
        }

        return false;
    }

    /**
     * @param string $apikey
     *
     * @return array
     */
    public function getInvalidChars($apikey)
    {
        $invalidChars = preg_replace('/[A-Za-z0-9+\/\=]/', '', $apikey);
        if ($invalidChars !== '') {
            return array_unique($this->str_split_unicode($invalidChars));
        }

        return [];
    }

    /**
     * @param string $str
     * @param int    $length
     *
     * @return array|array[]|false|string[]
     */
    private function str_split_unicode($str, $length = 1)
    {
        $tmp = preg_split('~~u', $str, -1, PREG_SPLIT_NO_EMPTY);
        if ($length > 1) {
            $chunks = array_chunk($tmp, $length);
            foreach ($chunks as $i => $chunk) {
                $chunks[$i] = implode('', (array)$chunk);
            }
            $tmp = $chunks;
        }

        return $tmp;
    }
}
