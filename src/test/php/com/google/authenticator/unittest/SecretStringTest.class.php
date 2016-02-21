<?php namespace com\google\authenticator\unittest;

use lang\FormatException;
use com\google\authenticator\SecretString;
use util\Secret;
use security\SecureString;
use util\Bytes;

class SecretStringTest extends \unittest\TestCase {
  const STRING = '2BX6RYQ4MD5M46KP';

  /** @return var[][] */
  private function fixtures() {
    $fixtures= [[self::STRING]];

    // FC with newer XP versions
    if (class_exists(Secret::class)) {
      $fixtures[]= [new Secret(self::STRING)];
    }

    // BC with older XP versions
    if (class_exists(SecureString::class)) {
      $fixtures[]= [new SecureString(self::STRING)];
    }
    return $fixtures;
  }

  #[@test, @values('fixtures')]
  public function can_create($arg) {
    new SecretString($arg);
  }

  #[@test, @expect(FormatException::class)]
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