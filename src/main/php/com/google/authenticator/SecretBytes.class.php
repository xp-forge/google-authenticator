<?php namespace com\google\authenticator;

use lang\types\Bytes;

/**
 * A secret based on raw bytes
 *
 * @test xp://com.google.authenticator.unittest.SecretBytesTest
 */
class SecretBytes extends \lang\Object implements Secret {
  private $bytes;

  /**
   * Creates a new secret string given base32-encoded bytes
   *
   * @param  var $arg Either a string or a Bytes instance
   * @throws lang.FormatException
   */
  public function __construct($bytes) {
    $this->bytes= $bytes instanceof Bytes ? $bytes->buffer : $bytes;
  }

  /** @return string */
  public function bytes() { return $this->bytes; }

  /**
   * Creates a string representation of this secret. Yields as many
   * asterisks as there are bytes in this secret.
   *
   * @return string
   */
  public function toString() {
    return $this->getClassName().'('.str_repeat('*', strlen($this->bytes)).')';
  }

  /** @return [:var] */
  public function __debugInfo() {
    return ['bytes' => str_repeat('*', strlen($this->bytes))];
  }
}