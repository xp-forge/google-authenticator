Google authenticator
====================

[![Build Status on TravisCI](https://secure.travis-ci.org/xp-forge/google-authenticator.svg)](http://travis-ci.org/xp-forge/google-authenticator)
[![XP Framework Module](https://raw.githubusercontent.com/xp-framework/web/master/static/xp-framework-badge.png)](https://github.com/xp-framework/core)
[![BSD Licence](https://raw.githubusercontent.com/xp-framework/web/master/static/licence-bsd.png)](https://github.com/xp-framework/core/blob/master/LICENCE.md)
[![Required PHP 5.4+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-5_4plus.png)](http://php.net/)
[![Required HHVM 3.4+](https://raw.githubusercontent.com/xp-framework/web/master/static/hhvm-3_4plus.png)](http://hhvm.com/)
[![Latest Stable Version](https://poser.pugx.org/xp-forge/google-authenticator/version.png)](https://packagist.org/packages/xp-forge/google-authenticator)

Supports one-time-passwords accordings (HOTP & TOTP) according to [RFC 4226](http://tools.ietf.org/html/rfc4226) and [RFC 6238](http://tools.ietf.org/html/rfc6238).

Example
-------
The following shows the API for time-based one-time passwords (TOTP):

```php
use com\google\authenticator\TimeBased;
use com\google\authenticator\SecretString;
use com\google\authenticator\Tolerance;
use security\SecureString;

$secret= new SecureString('2BX6RYQ4MD5M46KP');
$timebased= new TimeBased(new SecretString($secret));
$time= time();

// Get token for a given time
$token= $timebased->at($time);
$token= $timebased->current();

// Must match exactly
$verified= $timebased->verify($token, Tolerance::$NONE, $time);

// Allows previous and next
$verified= $timebased->verify($token);
$verified= $timebased->verify($token, null, $time);
$verified= $timebased->verify($token, Tolerance::$PREVIOUS_AND_NEXT, $time);
```

*Note: We use SecureString so that in case of exceptions, the secret will not appear in stack traces.*