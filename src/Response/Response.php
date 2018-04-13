<?php

/*
 * This file is part of the GestPayWS library.
 *
 * (c) Manuel Dalla Lana <endelwar@aregar.it>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EndelWar\GestPayWS\Response;

use EndelWar\GestPayWS\ArrayAccessTrait;
use Exception;

/**
 * Class Response
 */
abstract class Response implements \ArrayAccess
{
    use ArrayAccessTrait;

    /**
     * @param \SimpleXMLElement $xml
     * @throws Exception
     */
    public function __construct($xml)
    {
        $array = json_decode(json_encode($xml), true);
        $array = array_map(function ($value) {
            if (is_array($value) && empty($value)) {
                return '';
            }

            return $value;
        }, $array);
        $this->fromArray($array);
    }

    /**
     * @return mixed
     */
    public function toXML()
    {
        $data = $this->toArray();
        $xml = new \SimpleXMLElement('<GestPayCryptDecrypt/>');
        array_walk_recursive($data, function ($value, $key) use ($xml) {
            $xml->addChild($key, $value);
        });

        $dom = new \DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());

        return $dom->saveXML(null, LIBXML_NOEMPTYTAG);
    }

    public function isOK()
    {
        return $this->get('TransactionResult') === 'OK';
    }
}
