<?php
/*
 * This file is part of the GestPayWS library.
 *
 * (c) Manuel Dalla Lana <endelwar@aregar.it>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EndelWar\GestPayWS\Data;

/**
 * Class Data
 * @package EndelWar\GestPayWS\Data
 */
class Data
{
    /**
     * @param $costantName
     * @return bool|mixed
     */
    public static function getCode($costantName)
    {
        $costantName = strtoupper($costantName);
        if (defined("static::$costantName")) {
            return constant("static::$costantName");
        }

        return false;
    }
}
