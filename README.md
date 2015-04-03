# GestPayWS

[![Latest Stable Version](https://img.shields.io/packagist/v/endelwar/gestpayws.svg)](https://packagist.org/packages/endelwar/gestpayws)
[![Build Status](https://travis-ci.org/endelwar/GestPayWS.svg?branch=master)](https://travis-ci.org/endelwar/GestPayWS)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/endelwar/GestPayWS/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/endelwar/GestPayWS/?branch=master)
[![Coverage Status](https://coveralls.io/repos/endelwar/GestPayWS/badge.svg?branch=master)](https://coveralls.io/r/endelwar/GestPayWS?branch=master)
[![License](https://img.shields.io/packagist/l/endelwar/gestpayws.svg)](https://packagist.org/packages/endelwar/gestpayws)

PHP implementation of GestPay (Banca Sella) Web Services

## Highlights

- Simple API
- Decoupled classes
- PHPUnit tested
- Framework agnostic
- Composer ready, [PSR-2][] and [PSR-4][] compliant

## System Requirements

You need **PHP >= 5.3.3** and the `soap` extension to use the library, but the latest stable version of PHP is recommended.

## Install

Install `EndelWar/GestPayWS` using Composer.

```
$ composer require endelwar/gestpayws
```

## Using
### Crypt
``` php
require __DIR__ . '/../vendor/autoload.php';

use EndelWar\GestPayWS\WSCryptDecryptSoapClient;
use EndelWar\GestPayWS\WSCryptDecrypt;
use EndelWar\GestPayWS\Parameter\EncryptParameter;
use EndelWar\GestPayWS\Data;

// enable or disable test environment
$enableTestEnv = true;
$soapClient = new WSCryptDecryptSoapClient($enableTestEnv);
try {
    $gestpay = new WSCryptDecrypt($soapClient->getSoapClient());
} catch (\Exception $e) {
    var_dump($e->getCode(), $e->getMessage());
}

// set mandatory info
$encryptParameter = new EncryptParameter();
$encryptParameter->shopLogin = 'GESPAY12345';
$encryptParameter->amount = '1.23';
$encryptParameter->shopTransactionId = '1';
$encryptParameter->uicCode = Data\Currency::EUR;
$encryptParameter->languageId = Data\Language::ITALIAN;

// set optional custom info as array
$customArray = array('STORE_ID' => '42', 'STORE_NAME' => 'Shop Abc123');
$encryptParameter->setCustomInfo($customArray);

// encrypt data
$encryptResult = $gestpay->encrypt($encryptParameter);

// get redirect link to Banca Sella
echo $encryptResult->getPaymentPageUrl($encryptParameter->shopLogin, $soapClient->wsdlEnvironment);
```

### Decrypt
``` php
require __DIR__ . '/../vendor/autoload.php';

use EndelWar\GestPayWS\Parameter\DecryptParameter;
use EndelWar\GestPayWS\WSCryptDecryptSoapClient;
use EndelWar\GestPayWS\WSCryptDecrypt;

// $_GET['a'] and $_GET['b'] are received from Banca Sella
$param = array(
    'shopLogin' => $_GET['a'],
    'CryptedString' => $_GET['b']
);

$decryptParam = new DecryptParameter($param);

// enable or disable test environment
$enableTestEnv = true;
$soapClient = new WSCryptDecryptSoapClient($enableTestEnv);
try {
    $gestpay = new WSCryptDecrypt($soapClient->getSoapClient());
    $decryptResult = $gestpay->decrypt($decryptParam);
    
    echo $decryptResult->TransactionResult;
} catch (\Exception $e) {
    var_dump($e->getCode(), $e->getMessage());
}
```

## Testing

`EndelWar/GestPayWS` has a [PHPUnit](https://phpunit.de) test suite. To run the tests, run the following command from the project folder.

``` bash
$ phpunit
```

## Contributing

1. Fork it
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Add some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create new Pull Request

## Versioning

Semantic versioning ([semver](http://semver.org/)) is applied.

## License

This library is under the MIT license. For the full copyright and license information, please view the LICENSE file that
was distributed with this source code.

[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md