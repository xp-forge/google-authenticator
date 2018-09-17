<?php namespace com\google\authenticator;

use util\Random;

/**
 * Secrets utility class
 *
 * @test  xp://com.google.authenticator.unittest.SecretsTest
 */
abstract class Secrets {
  const LENGTH = 10;

  /**
   * Returns a new random secret. Uses the `util.Random` class.
   *
   * @see    xp://util.Random
   * @return com.google.authenticator.Secret
   */
  public static function random() {
    return new SecretBytes((new Random())->bytes(self::LENGTH));
  }
}