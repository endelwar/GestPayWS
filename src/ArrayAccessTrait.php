<?php
/*
 * This file is part of the GestPayWS library.
 *
 * (c) Manuel Dalla Lana <endelwar@aregar.it>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EndelWar\GestPayWS;

trait ArrayAccessTrait
{
    protected $data = [];
    protected $parametersName = [];

    /**
     * @param string $key
     * @param mixed $value
     * @throws \InvalidArgumentException
     */
    public function set($key, $value)
    {
        if (!in_array($key, $this->parametersName, true)) {
            throw new \InvalidArgumentException(sprintf('%s is not a valid parameter name.', $key));
        }
        $this->data[$key] = $value;
    }

    /**
     * @param string $key
     *
     * @return mixed|null the value at the specified index or null
     */
    public function get($key)
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        return null;
    }

    /**
     * @param array $data
     * @throws \InvalidArgumentException
     */
    public function fromArray($data)
    {
        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * Returns whether the requested index exists
     *
     * @param string $key
     *
     * @return bool
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * Returns the value at the specified index
     *
     * @param string $key the index with the value
     *
     * @return mixed|null the value at the specified index or null
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * Sets the value at the specified index to $value
     *
     * @param string $key the index being set
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
            return $this->$getter();
        }

        return $this->get($key);
    }

    /**
     * Magic setter, calls setXXX if exists.
     * @param $key
     * @param $value
     *
     * @throws \InvalidArgumentException
     * @return mixed
     */
    public function __set($key, $value)
    {
        $setter = 'set' . $this->classify($key);
        if (method_exists($this, $setter)) {
            return $this->$setter($value);
        }
        $this->set($key, $value);
    }

    /**
     * Returns whether the requested index exists
     *
     * @param string $key
     *
     * @return bool
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

    /**
     * Converts a string into a CamelCase word.
     *
     * @param string $string the string to classify
     *
     * @return string the classified word
     */
    private function classify($string)
    {
        return str_replace(' ', '', ucwords(strtr($string, '_-', ' ')));
    }
}
