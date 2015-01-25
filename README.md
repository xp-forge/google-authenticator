Google authenticator
====================

[![Build Status on TravisCI](https://secure.travis-ci.org/xp-forge/google-authenticator.svg)](http://travis-ci.org/xp-forge/google-authenticator)
[![XP Framework Module](https://raw.githubusercontent.com/xp-framework/web/master/static/xp-framework-badge.png)](https://github.com/xp-framework/core)
[![BSD Licence](https://raw.githubusercontent.com/xp-framework/web/master/static/licence-bsd.png)](https://github.com/xp-framework/core/blob/master/LICENCE.md)
[![Required PHP 5.4+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-5_4plus.png)](http://php.net/)
[![Latest Stable Version](https://poser.pugx.org/xp-forge/google-authenticator/version.png)](https://packagist.org/packages/xp-forge/google-authenticator)

Supports one-time-passwords accordings (HOTP & TOTP) according to [RFC 4226](http://tools.ietf.org/html/rfc4226) and [RFC 6238](http://tools.ietf.org/html/rfc6238).

Example
-------

```php
use com\google\authenticator\TimeBased;
use com\google\authenticator\SecretString;
use security\SecureString;

$secret= new SecretString(new SecureString('2BX6RYQ4MD5M46KP'));
$timebased= new TimeBased($secret);

$time= time();

// Get token for a given time
$token= $timebased->at($time);
$token= $timebased->current();

// Verify token for a given time
$verified= $timebased->verify($token, $time);
$verified= $timebased->verify($token);
```