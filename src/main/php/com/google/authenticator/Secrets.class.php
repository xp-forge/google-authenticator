<?php namespace com\google\authenticator;

/**
 * Secrets utility class
 *
 * @test  xp://com.google.authenticator.unittest.SecretsTest
 */
abstract class Secrets {
  const LENGTH = 10;

  /**
   * Returns a new random secret. Uses the OpenSSL and MCrypt libraries
   * if available, falling back to the system random number generator.
   *
   * @see    php://openssl_random_pseudo_bytes
   * @see    php://mcrypt_create_iv
   * @see    php://rand
   * @return com.google.authenticator.Secret
   */
  public static function random() {
    if (function_exists('openssl_random_pseudo_bytes')) {
      $bytes= openssl_random_pseudo_bytes(self::LENGTH);
    } else if (function_exists('mcrypt_create_iv')) {
      $bytes= mcrypt_create_iv(self::LENGTH, MCRYPT_DEV_RANDOM);
    } else {
      $bytes= '';
      for ($i= 0; $i < self::LENGTH; $i++) {
        $bytes.= chr(rand(0, 255));
      }
    }
    return new SecretBytes($bytes);
  }
}