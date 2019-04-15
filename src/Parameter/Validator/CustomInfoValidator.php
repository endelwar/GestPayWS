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

class CustomInfoValidator implements ParameterValidatorInterface
{
    /**
     * @param string $parameter
     *
     * @return bool
     */
    public function isValid($parameter)
    {
        // TODO: Implement isValid() method.
    }

    /**
     * @param string $parameter
     *
     * @return array
     */
    public function getInvalidChars($parameter)
    {
        // TODO: Implement getInvalidChars() method.
        return [];
    }
}
