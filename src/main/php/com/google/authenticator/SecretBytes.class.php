<?php namespace com\google\authenticator;

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
    parent::__construct((string)$bytes);
  }
}