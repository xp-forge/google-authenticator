<?php namespace com\google\authenticator;

use lang\IllegalStateException;

class Secret extends \lang\Object {
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
    return nameof($this).'('.str_repeat('*', strlen($this->bytes)).')';
  }

  /** @return [:var] */
  public function __debugInfo() {
    return ['bytes' => str_repeat('*', strlen($this->bytes))];
  }

  /**
   * Returns a new random secret. Uses the OpenSSL and MCrypt libraries
   * if available, falling back to the system random number generator.
   *
   * @deprecated Use Secrets::random() instead
   * @return self
   */
  public static function random() { return Secrets::random(); }
}