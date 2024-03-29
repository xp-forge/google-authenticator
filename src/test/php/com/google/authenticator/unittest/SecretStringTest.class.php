<?php namespace com\google\authenticator\unittest;

use com\google\authenticator\SecretString;
use lang\FormatException;
use test\{Assert, Expect, Test, Values};
use util\{Bytes, Secret};

class SecretStringTest {
  const STRING = '2BX6RYQ4MD5M46KP';

  /** @return iterable */
  private function fixtures() {
    yield [self::STRING];
    yield [new Secret(self::STRING)];
  }

  #[Test, Values(from: 'fixtures')]
  public function can_create($arg) {
    new SecretString($arg);
  }

  #[Test, Expect(FormatException::class)]
  public function raises_exception_for_invalid_base32_input() {
    new SecretString('äöü');
  }

  #[Test, Values(from: 'fixtures')]
  public function encoded($arg) {
    Assert::equals(self::STRING, (new SecretString($arg))->encoded());
  }

  #[Test, Values(from: 'fixtures')]
  public function bytes($arg) {
    Assert::equals(new Bytes("\320o\350\342\034`\372\316yO"), new Bytes((new SecretString($arg))->bytes()));
  }

  #[Test]
  public function string_representation() {
    Assert::equals(
      'com.google.authenticator.SecretString(**********)',
      (new SecretString(self::STRING))->toString()
    );
  }
}