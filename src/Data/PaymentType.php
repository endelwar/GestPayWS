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
 * Class PaymentType
 */
class PaymentType extends Data
{
    const MONEYTRANSFER = 'BON';
    const CREDITCARD = 'CREDITCARD';
    const PAYPAL = 'PAYPAL';
    const MYBANK = 'MYBANK';
    const MOBILEQRCODE = 'UPMOBILE';
    const MASTERPASS = 'MASTERPASS';
    const SOFORT = 'SOFORT';
    const IDEAL = 'IDEAL';
    const CONSEL = 'CONSEL';
}
