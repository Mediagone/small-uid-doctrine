# Small UID for Doctrine

⚠️ _This project is in experimental phase, the API may change any time._

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Software License][ico-license]](LICENSE)

Provides Doctrine types for `mediagone/small-uid` package:
- `SmallUidType`: binary (8 bytes) 


## Installation

This package requires **PHP (64-bit) 7.4+** and **Doctrine DBAL 2.7+**

Add it as Composer dependency:
```sh
$ composer require mediagone/small-uid-doctrine
```


### With Symfony

If you're using this package in a Symfony project, register _Small UID_ custom type in doctrine.yaml:
```yaml
doctrine:
    dbal:
        types:
            smalluid: Mediagone\SmallUid\Doctrine\SmallUidType
```
_Note: `smalluid` being the type name you'll use in your Entity mappings, you can pick whatever name you wish._


### As standalone
Custom types can also be used separately, but need to be registered in Doctrine DBAL like this:
```php
use Doctrine\DBAL\Types\Type;
use Mediagone\SmallUid\Doctrine\SmallUidType;

Type::addType(SmallUidType::NAME, SmallUidType::class);
// or, with a custom name:
Type::addType('smalluid', SmallUidType::class);
```



## License

_Small UID for Doctrine_ is licensed under MIT license. See LICENSE file.


[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg
[ico-version]: https://img.shields.io/packagist/v/mediagone/small-uid-doctrine.svg
[ico-downloads]: https://img.shields.io/packagist/dt/mediagone/small-uid-doctrine.svg

[link-packagist]: https://packagist.org/packages/mediagone/small-uid-doctrine
[link-downloads]: https://packagist.org/packages/mediagone/small-uid-doctrine
