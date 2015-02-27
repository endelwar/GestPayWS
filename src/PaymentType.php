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

/**
 * Class PaymentTypes
 * @package EndelWar\GestPayWS
 */
class PaymentType extends Data
{
    const MoneyTransfer = 'BON';
    const CreditCard = 'CREDITCARD';
    const PayPal = 'PAYPAL';
    const MyBank = 'MYBANK';
    const MobileQRCode = 'UPMOBILE';
    const MasterPass = 'MASTERPASS';
    const Sofort = 'SOFORT';
    const Ideal = 'IDEAL';
    const Consel = 'CONSEL';
}
