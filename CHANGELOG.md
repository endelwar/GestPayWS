# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased][unreleased]

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

[unreleased]: https://github.com/endelwar/GestPayWS/compare/v1.1.2...HEAD
[1.1.2]: https://github.com/endelwar/GestPayWS/compare/v1.1.1...v1.1.2
[1.1.1]: https://github.com/endelwar/GestPayWS/compare/v1.1.0...v1.1.1
[1.1.0]: https://github.com/endelwar/GestPayWS/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/endelwar/GestPayWS/compare/v0.1.0...v1.0.0
[0.1.0]: https://github.com/endelwar/GestPayWS/compare/67d07c5c9c4d1873ba9620af25b91e0a53664d80...v0.1.0