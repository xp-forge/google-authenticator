<?php namespace com\google\authenticator;

use lang\types\Bytes;

/**
 * A secret based on raw bytes
 *
 * @test xp://com.google.authenticator.unittest.SecretBytesTest
 */
class SecretBytes extends Secret {

  /**
   * Creates a new secret string given base32-encoded bytes
   *
   * @param  var $arg Either a string or a Bytes instance
   * @throws lang.FormatException
   */
  public function __construct($bytes) {
    parent::__construct($bytes instanceof Bytes ? $bytes->buffer : $bytes);
  }
}