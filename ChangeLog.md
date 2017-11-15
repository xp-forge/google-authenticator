Google Authenticator ChangeLog
==============================

## ?.?.? / ????-??-??

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

* Bump version of xp-framework/core dependency to ~6.0
  (@kiesel)

## 0.2.0 / 2015-02-07

* Heads up: `Secret` is now a class and no longer an interface
  (@thekid)
* Implemented `Secret::random()` as requested and drafted in issue #2.
  (@thekid)

## 0.1.0 / 2014-12-21

* Hello World! First release - @thekid