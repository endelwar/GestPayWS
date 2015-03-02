<?php


namespace EndelWar\GestPayWS\Parameter;

use InvalidArgumentException;

/**
 * Class Parameter
 * @package EndelWar\GestPayWS\Parameter
 */
abstract class Parameter implements \ArrayAccess
{
    protected $parameters = array();
    protected $parametersName = array();

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        if (!in_array($key, $this->parametersName)) {
            throw new InvalidArgumentException(sprintf('%s is not a valid parameter name.', $key));
        }
        $this->parameters[$key] = $value;
    }

    /**
     * @param string $key
     *
     * @return mixed|null The value at the specified index or null.
     */
    public function get($key)
    {
        if (array_key_exists($key, $this->parameters)) {
            return $this->parameters[$key];
        }

        return null;
    }

    /**
     * @param array $array
     */
    public function fromArray($array)
    {
        foreach ($array as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->parameters;
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
        return array_key_exists($key, $this->parameters);
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
        unset($this->parameters[$key]);
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
        return isset($this->parameters[$key]);
    }

    /**
     * Unsets the value at the specified index
     *
     * @param string $key
     */
    public function __unset($key)
    {
        unset($this->parameters[$key]);
    }
}
