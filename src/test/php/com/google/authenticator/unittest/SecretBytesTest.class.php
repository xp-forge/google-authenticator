<?php namespace com\google\authenticator\unittest;

use com\google\authenticator\SecretBytes;
use unittest\{Test, Values};
use util\Bytes;

class SecretBytesTest extends \unittest\TestCase {
  const BYTES = "\320o\350\342\034`\372\316yO";

  /** @return var[][] */
  private function fixtures() {
    return [[self::BYTES], [new Bytes(self::BYTES)]];
  }

  #[Test, Values('fixtures')]
  public function can_create($arg) {
    new SecretBytes($arg);
  }

  #[Test, Values('fixtures')]
  public function encoded($arg) {
    $this->assertEquals('2BX6RYQ4MD5M46KP', (new SecretBytes($arg))->encoded());
  }

  #[Test, Values('fixtures')]
  public function bytes($arg) {
    $this->assertEquals(new Bytes(self::BYTES), new Bytes((new SecretBytes($arg))->bytes()));
  }

  #[Test]
  public function string_representation() {
    $this->assertEquals(
      'com.google.authenticator.SecretBytes(**********)',
      (new SecretBytes(self::BYTES))->toString()
    );
  }
}