<?php namespace com\google\authenticator;

use lang\IllegalStateException;

class Secret extends \lang\Object {
  const LENGTH = 10;

  private $bytes;

  /**
   * Creates a new secret
   *
   * @param  string $bytes The raw bytes in this secret
   */
  public function __construct($bytes) {
    $this->bytes= $bytes;
  }

  /**
   * Returns the underlying raw bytes of this secret
   *
   * @return string
   */
  public function bytes() {
    return $this->bytes;
  }

  /**
   * Returns the encoded version
   *
   * @return string
   */
  public function encoded() {
    static $alphabet= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    $length= strlen($this->bytes);
    if (0 === $length) {
      return '';
    } else if ($length >= (1 << 28)) {
      throw new IllegalStateException('Cannot encode value, too long ('.$length.')');
    }

    $buffer= ord($this->bytes{0});
    $next= 1;
    $left= 8;
    $result= '';
    while (($left > 0 || $next < $length)) {
      if ($left < 5) {
        if ($next < $length) {
          $buffer= $buffer << 8 | ord($this->bytes{$next++}) & 0xff;
          $left+= 8;
        } else {
          $pad= 5 - $left;
          $buffer <<= $pad;
          $left+= $pad;
        }
      }

      $result.= $alphabet{0x1f & ($buffer >> ($left-= 5))};
    }

    return $result;
  }

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
    return new self($bytes);
  }
}