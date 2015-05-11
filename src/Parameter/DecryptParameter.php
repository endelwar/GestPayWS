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

/**
 * Class DecryptParameter
 * @package EndelWar\GestPayWS\Parameter
 */
class DecryptParameter extends Parameter
{
    protected $parametersName = array(
        'shopLogin',
        'CryptedString',
    );

    protected $mandatoryParameters = array(
        'shopLogin',
        'CryptedString',
    );
}
