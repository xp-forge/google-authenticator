<?php namespace com\google\authenticator\unittest;

use com\google\authenticator\SecretString;
use security\SecureString;
use lang\types\Bytes;

class SecretStringTest extends \unittest\TestCase {
  const STRING = '2BX6RYQ4MD5M46KP';

  /** @return var[][] */
  private function fixtures() {
    return [[self::STRING], [new SecureString(self::STRING)]];
  }

  #[@test, @values('fixtures')]
  public function can_create($arg) {
    new SecretString($arg);
  }

  #[@test, @expect('lang.FormatException')]
  public function raises_exception_for_invalid_base32_input() {
    new SecretString('äöü');
  }

  #[@test, @values('fixtures')]
  public function encoded($arg) {
    $this->assertEquals(self::STRING, (new SecretString($arg))->encoded());
  }

  #[@test, @values('fixtures')]
  public function bytes($arg) {
    $this->assertEquals(new Bytes("\320o\350\342\034`\372\316yO"), new Bytes((new SecretString($arg))->bytes()));
  }

  #[@test]
  public function string_representation() {
    $this->assertEquals(
      'com.google.authenticator.SecretString(**********)',
      (new SecretString(self::STRING))->toString()
    );
  }
}