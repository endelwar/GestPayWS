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

use InvalidArgumentException;

/**
 * Class Response
 * @package EndelWar\GestPayWS\Response
 */
abstract class Response implements \ArrayAccess
{
    protected $data = array();
    protected $parametersName = array();

    /**
     * @param $key
     * @param $value
     * @throws InvalidArgumentException
     */
    public function set($key, $value)
    {
        if (!in_array($key, $this->parametersName)) {
            throw new InvalidArgumentException(sprintf('%s is not a valid parameter name.', $key));
        }
        $this->data[$key] = $value;
    }

    /**
     * @param string $key
     *
     * @return mixed|null The value at the specified index or null.
     */
    public function get($key)
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        return null;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function fromArray($data)
    {
        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * Returns whether the requested index exists
     *
     * @param string $key
     *
     * @return boolean
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * Returns the value at the specified index
     *
     * @param string $key The index with the value.
     *
     * @return mixed|null The value at the specified index or null.
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * Sets the value at the specified index to $value
     *
     * @param string $key The index being set.
     * @param mixed $value The new value for the index
     */
    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Unsets the value at the specified index
     *
     * @param string $key
     */
    public function offsetUnset($key)
    {
        unset($this->data[$key]);
    }

    /**
     * Magic getter, calls getXXX if exists.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        $getter = 'get' . $this->classify($key);
        if (method_exists($this, $getter)) {
            return call_user_func(array($this, $getter));
        }

        return $this->get($key);
    }

    /**
     * Magic setter, calls getXXX if exists.
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function __set($key, $value)
    {
        $setter = 'set' . $this->classify($key);
        if (method_exists($this, $setter)) {
            return call_user_func_array(array($this, $setter), array($value));
        }
        $this->set($key, $value);
    }

    /**
     * Converts a string into a CamelCase word.
     *
     * @param string $string The string to classify.
     *
     * @return string The classified word.
     */
    private function classify($string)
    {
        return str_replace(' ', '', ucwords(strtr($string, "_-", " ")));
    }

    /**
     * Returns whether the requested index exists
     *
     * @param string $key
     *
     * @return boolean
     */
    public function __isset($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * Unsets the value at the specified index
     *
     * @param string $key
     */
    public function __unset($key)
    {
        unset($this->data[$key]);
    }
}
