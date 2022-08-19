Google Authenticator ChangeLog
==============================

## ?.?.? / ????-??-??

## 5.1.0 / 2022-08-19

* Merged PR #4: Allow passing util.Secret instances to time- and counter
  based algorithms directly (the "be liberal in what you accept" paradigm)
  (@thekid)
* Merged PR #3: Add `provisioningUri()` method to time- and counter-based
  algorithms
  (@thekid)

## 5.0.1 / 2021-10-21

* Made library compatible with XP 11 - @thekid

## 5.0.0 / 2020-04-10

* Implemented xp-framework/rfc#334: Drop PHP 5.6:
  . **Heads up:** Minimum required PHP version now is PHP 7.0.0
  (@thekid)

## 4.0.1 / 2020-04-04

* Made compatible with XP 10 - @thekid

## 4.0.0 / 2018-09-17

* **Heads up**: Dropped support for XP6, minimum required XP version
  is now 7.3.0, which includes the `util.Random` class
  (@thekid)
* Replaced random implementation inside `Secrets` with `util.Random`
  from the XP Framework
  (@thekid)

## 3.1.0 / 2017-11-16

* Prevented `Secret` class from being used directly; either instantiate
  a `SecretBytes` or `SecretString` class instead!
  (@thekid)

## 3.0.0 / 2017-11-15

* **Heads up: Dropped PHP 5.5 support** - @thekid
* Added compatibility with XP 8 and XP 9 - @thekid
* Implemented `lang.Value` instead of extending `lang.Object` - @thekid

## 2.0.0 / 2016-02-21

* Added version compatibility with XP 7 - @thekid

## 1.0.1 / 2016-01-23

* Fix code to use `nameof()` instead of the deprecated `getClassName()`
  method from lang.Generic. See xp-framework/core#120
  (@thekid)

## 1.0.0 / 2015-12-14

* **Heads up**: Changed minimum XP version to XP 6.5.0, and with it the
  minimum PHP version to PHP 5.5.
  (@thekid)
* Created a new utility class `com.google.authenticator.Secrets` which
  now hosts the `random()` method. This is to avoid importing conflicts
  with the new `util.Secret` class (*see below*).
  (@thekid)
* Added support for new `util.Secret` class introduced in XP 6.8.0
  (@thekid)
* Added official support for PHP 7.0
  (@thekid)

## 0.2.1 / 2015-02-12

* Bump version of xp-framework/core dependency to 6.0
  (@kiesel)

## 0.2.0 / 2015-02-07

* Heads up: `Secret` is now a class and no longer an interface
  (@thekid)
* Implemented `Secret::random()` as requested and drafted in issue #2.
  (@thekid)

## 0.1.0 / 2014-12-21

* Hello World! First release - @thekid