# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased][unreleased]
### Added
- Add support for `apikey` parameter
- Add `~` to invalid chars in string

### Changed
- Remove support for EOL PHP version: PHP 5.6.7 required

### Fixed
- Update sandbox urls

## [1.3.1] - 2018-02-09
### Fixed
- Update sandbox urls
- Fixed crypto_method for soap stream context since php 5.6.7 and above
- Fixed missing parameters name for DecryptResponse

## [1.3.0] - 2015-11-06
### Changed
- Add certificate peer validation on php < 5.6

## [1.2.1] - 2015-10-23
### Changed
- toXML method returns formatted XML

## [1.2.0] - 2015-10-23
### Added
- toXML method on Response

## [1.1.2] - 2015-10-13
### Fixed
- Use triple equal sign in Response->isOK()
- Removed old code from .styleci.yml file

## [1.1.1] - 2015-05-11
### Fixed
- Fixed PSR2 compatibility and code cleanup

## [1.1.0] - 2015-05-11
### Added
- Remove exception if Response is not OK
- Five more tests to Response/DecryptResponse
- Missing PHPdoc

### Fixed
- Typo on PHPdoc of Response/Response `__set` method

## [1.0.0] - 2015-03-31
### Added
- PHPDoc private method getStreamContext
- Documented `crypt` and `decrypt` methods in readme

### Changed
- Bump user agent version to 1.0

## [0.1.0] - 2015-03-31
### Added
- Data abstract class for managing constant data (Currency id, Language id)
- Parameter abstract class to be extended in EncryptParameter and DecryptParameter
- Response abstract class to be extended in EncryptResponse and DecryptResponse
- WSCryptDecrypt class
- WSCryptDecryptSoapClient class
- PHPUnit Test Suite

[unreleased]: https://github.com/endelwar/GestPayWS/compare/v1.3.1...HEAD
[1.3.1]: https://github.com/endelwar/GestPayWS/compare/v1.3.0...v1.3.1
[1.3.0]: https://github.com/endelwar/GestPayWS/compare/v1.2.1...v1.3.0
[1.2.1]: https://github.com/endelwar/GestPayWS/compare/v1.2.0...v1.2.1
[1.2.0]: https://github.com/endelwar/GestPayWS/compare/v1.1.2...v1.2.0
[1.1.2]: https://github.com/endelwar/GestPayWS/compare/v1.1.1...v1.1.2
[1.1.1]: https://github.com/endelwar/GestPayWS/compare/v1.1.0...v1.1.1
[1.1.0]: https://github.com/endelwar/GestPayWS/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/endelwar/GestPayWS/compare/v0.1.0...v1.0.0
[0.1.0]: https://github.com/endelwar/GestPayWS/compare/67d07c5c9c4d1873ba9620af25b91e0a53664d80...v0.1.0
