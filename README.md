#GestPayWS

[![Latest Stable Version](https://poser.pugx.org/endelwar/gestpayws/v/stable.svg)](https://packagist.org/packages/endelwar/gestpayws)
[![Build Status](https://travis-ci.org/endelwar/GestPayWS.svg?branch=master)](https://travis-ci.org/endelwar/GestPayWS)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/endelwar/GestPayWS/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/endelwar/GestPayWS/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/endelwar/GestPayWS/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/endelwar/GestPayWS/?branch=master)
[![License](https://img.shields.io/packagist/l/endelwar/gestpayws.svg)](https://packagist.org/packages/endelwar/gestpayws)

PHP implementation of GestPay (Banca Sella) Web Services

##Highlights

- Simple API
- PHPUnit tested
- Framework agnostic
- Composer ready, [PSR-2][] and [PSR-4][] compliant

##System Requirements

You need **PHP >= 5.3.3** and the `soap` extension to use the library, but the latest stable version of PHP is recommended.

##Install

Install `EndelWar/GestPayWS` using Composer.

```
$ composer require endelwar/gestpayws
```

##Testing

`EndelWar/GestPayWS` has a [PHPUnit](https://phpunit.de) test suite. To run the tests, run the following command from the project folder.

``` bash
$ phpunit
```

## Versioning

Semantic versioning ([semver](http://semver.org/)) is applied.

## License

This bundle is under the MIT license. For the full copyright and license information, please view the LICENSE file that
was distributed with this source code.

[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md