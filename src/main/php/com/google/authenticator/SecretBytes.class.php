<?php namespace com\google\authenticator;

use util\Bytes;

/**
 * A secret based on raw bytes
 *
 * @test xp://com.google.authenticator.unittest.SecretBytesTest
 */
class SecretBytes extends Secret {

  /**
   * Creates a new secret string given base32-encoded bytes
   *
   * @param  util.Bytes|string $bytes
   * @throws lang.FormatException
   */
  public function __construct($bytes) {
    if ($bytes instanceof \lang\types\Bytes) {
      parent::__construct($bytes->buffer);
    } else {
      parent::__construct((string)$bytes);
    }
  }
}