<?php

/*
 * This file is part of the GestPayWS library.
 *
 * (c) Manuel Dalla Lana <endelwar@aregar.it>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EndelWar\GestPayWS\Parameter;

use EndelWar\GestPayWS\ArrayAccessTrait;

/**
 * Class Parameter
 */
abstract class Parameter implements \ArrayAccess
{
    use ArrayAccessTrait;

    protected $mandatoryParameters = [];

    /**
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        foreach ($this->parametersName as $parameterName) {
            $this->set($parameterName, null);
        }
        if (!empty($parameters)) {
            $this->fromArray($parameters);
        }
    }

    /**
     * @return bool
     */
    public function areAllMandatoryParametersSet()
    {
        foreach ($this->mandatoryParameters as $param) {
            if (!isset($this->$param)) {
                return false;
            }
        }

        return true;
    }
}
