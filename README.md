Google authenticator
====================

[![Build status on GitHub](https://github.com/xp-forge/google-authenticator/workflows/Tests/badge.svg)](https://github.com/xp-forge/google-authenticator/actions)
[![XP Framework Module](https://raw.githubusercontent.com/xp-framework/web/master/static/xp-framework-badge.png)](https://github.com/xp-framework/core)
[![BSD Licence](https://raw.githubusercontent.com/xp-framework/web/master/static/licence-bsd.png)](https://github.com/xp-framework/core/blob/master/LICENCE.md)
[![Requires PHP 7.4+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-7_4plus.svg)](http://php.net/)
[![Supports PHP 8.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-8_0plus.svg)](http://php.net/)
[![Latest Stable Version](https://poser.pugx.org/xp-forge/google-authenticator/version.svg)](https://packagist.org/packages/xp-forge/google-authenticator)

Supports one-time passwords accordings (HOTP & TOTP) according to [RFC 4226](http://tools.ietf.org/html/rfc4226) and [RFC 6238](http://tools.ietf.org/html/rfc6238).

Working with one-time passwords
-------------------------------
The following shows the API for time-based one-time passwords (TOTP):

```php
use com\google\authenticator\{TimeBased, Tolerance};
use util\Secret;

$secret= new Secret('2BX6RYQ4MD5M46KP');
$timebased= new TimeBased($secret);
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
use com\google\authenticator\{CounterBased, Tolerance};
use util\Secret;

$secret= new Secret('2BX6RYQ4MD5M46KP');
$counterbased= new CounterBased($secret);
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
As an issuer of OTPs, you need to create random secrets in order to seed both client and server. Using the *provisioningUri()* method, you can fetch the URIs used to configure the clients.

```php
use com\google\authenticator\{CounterBased, TimeBased, Secrets};

$random= Secrets::random();

// HOTP, otpauth://hotp/{account}?secret={secret}&counter={counter}
$counterbased= new CounterBased($random);
$uri= $counterbased->provisioningUri($account);             // Start with counter= 0
$uri= $counterbased->provisioningUri($account, $initial);   // Start with counter= $initial

// TOTP, otpauth://totp/{account}?secret={secret}
$timebased= new TimeBased($random);
$uri= $timebased->provisioningUri($account);

// Pass a map of string to append additional parameters
$uri= $timebased->provisioningUri($account, ['issuer' => 'ACME Co']);

// Pass an array to namespace the account, yields "ACME%20Co:user@example.com"
$uri= $timebased->provisioningUri(['ACME Co', 'user@example.com']);
```