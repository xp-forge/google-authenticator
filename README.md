Google authenticator
====================

[![Build Status on TravisCI](https://secure.travis-ci.org/xp-forge/google-authenticator.svg)](http://travis-ci.org/xp-forge/google-authenticator)
[![XP Framework Module](https://raw.githubusercontent.com/xp-framework/web/master/static/xp-framework-badge.png)](https://github.com/xp-framework/core)
[![BSD Licence](https://raw.githubusercontent.com/xp-framework/web/master/static/licence-bsd.png)](https://github.com/xp-framework/core/blob/master/LICENCE.md)
[![Required PHP 5.5+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-5_5plus.png)](http://php.net/)
[![Supports PHP 7.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-7_0plus.png)](http://php.net/)
[![Supports HHVM 3.4+](https://raw.githubusercontent.com/xp-framework/web/master/static/hhvm-3_4plus.png)](http://hhvm.com/)
[![Latest Stable Version](https://poser.pugx.org/xp-forge/google-authenticator/version.png)](https://packagist.org/packages/xp-forge/google-authenticator)

Supports one-time passwords accordings (HOTP & TOTP) according to [RFC 4226](http://tools.ietf.org/html/rfc4226) and [RFC 6238](http://tools.ietf.org/html/rfc6238).

Working with one-time passwords
-------------------------------
The following shows the API for time-based one-time passwords (TOTP):

```php
use com\google\authenticator\{TimeBased, SecretString, Tolerance};
use util\Secret;

$secret= new Secret('2BX6RYQ4MD5M46KP');
$timebased= new TimeBased(new SecretString($secret));
$time= time();

// Get token for a given time
$token= $timebased->at($time);
$token= $timebased->current();

// Must match exactly
$verified= $timebased->verify($token, $time, Tolerance::$NONE);

// Allows previous and next
$verified= $timebased->verify($token);
$verified= $timebased->verify($token, $time);
$verified= $timebased->verify($token, $time, Tolerance::$PREVIOUS_AND_NEXT);
```

The following shows the API for counter-based one-time passwords (HOTP):

```php
use com\google\authenticator\{CounterBased, SecretString, Tolerance};
use util\Secret;

$secret= new Secret('2BX6RYQ4MD5M46KP');
$counterbased= new CounterBased(new SecretString($secret));
$counter= 0;

// Get token for a given counter
$token= $counterbased->at($counter);

// Must match exactly
$verified= $counterbased->verify($token, $counter, Tolerance::$NONE);

// Allows previous and next
$verified= $counterbased->verify($token, $counter);
$verified= $counterbased->verify($token, $counter, Tolerance::$PREVIOUS_AND_NEXT);
```

*Note: We use util.Secret so that in case of exceptions, the secret will not appear in stack traces.*

Creating secrets
----------------
As an issuer of OTPs, you need to create random secrets in order to seed both client and server.

```php
use com\google\authenticator\Secrets;

$random= Secrets::random();

// Present to client using TOTP
$url= 'otpauth://totp/'.urlencode($username).'?secret='.$random->encoded();
```
