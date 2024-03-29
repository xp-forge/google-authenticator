<?php namespace com\google\authenticator\unittest;

use com\google\authenticator\SecretBytes;
use test\{Assert, Test, Values};
use util\Bytes;

class SecretBytesTest {
  const BYTES = "\320o\350\342\034`\372\316yO";

  /** @return iterable */
  private function fixtures() {
    yield [self::BYTES];
    yield [new Bytes(self::BYTES)];
  }

  #[Test, Values(from: 'fixtures')]
  public function can_create($arg) {
    new SecretBytes($arg);
  }

  #[Test, Values(from: 'fixtures')]
  public function encoded($arg) {
    Assert::equals('2BX6RYQ4MD5M46KP', (new SecretBytes($arg))->encoded());
  }

  #[Test, Values(from: 'fixtures')]
  public function bytes($arg) {
    Assert::equals(new Bytes(self::BYTES), new Bytes((new SecretBytes($arg))->bytes()));
  }

  #[Test]
  public function string_representation() {
    Assert::equals(
      'com.google.authenticator.SecretBytes(**********)',
      (new SecretBytes(self::BYTES))->toString()
    );
  }
}