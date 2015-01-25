<?php namespace com\google\authenticator;

use lang\FormatException;
use security\SecureString;

/**
 * A secret based on a base32-encoded string 
 *
 * @see  http://tools.ietf.org/html/rfc3548
 * @see  http://tools.ietf.org/html/rfc4648
 * @test xp://com.google.authenticator.unittest.SecretStringTest
 */
class SecretString extends \lang\Object implements Secret {
  private $bytes;

  /**
   * Creates a new secret string given base32-encoded bytes
   *
   * @param  var $arg Either a string or a SecureString instance
   * @throws lang.FormatException
   */
  public function __construct($arg) {
    static $table= [
      '2' => 0x1a, '3' => 0x1b, '4' => 0x1c, '5' => 0x1d, '6' => 0x1e, '7' => 0x1f,
      'A' => 0x00, 'B' => 0x01, 'C' => 0x02, 'D' => 0x03, 'E' => 0x04, 'F' => 0x05,
      'G' => 0x06, 'H' => 0x07, 'I' => 0x08, 'J' => 0x09, 'K' => 0x0a, 'L' => 0x0b,
      'M' => 0x0c, 'N' => 0x0d, 'O' => 0x0e, 'P' => 0x0f, 'Q' => 0x10, 'R' => 0x11,
      'S' => 0x12, 'T' => 0x13, 'U' => 0x14, 'V' => 0x15, 'W' => 0x16, 'X' => 0x17,
      'Y' => 0x18, 'Z' => 0x19, 'a' => 0x00, 'b' => 0x01, 'c' => 0x02, 'd' => 0x03,
      'e' => 0x04, 'f' => 0x05, 'g' => 0x06, 'h' => 0x07, 'i' => 0x08, 'j' => 0x09,
      'k' => 0x0a, 'l' => 0x0b, 'm' => 0x0c, 'n' => 0x0d, 'o' => 0x0e, 'p' => 0x0f,
      'q' => 0x10, 'r' => 0x11, 's' => 0x12, 't' => 0x13, 'u' => 0x14, 'v' => 0x15,
      'w' => 0x16, 'x' => 0x17, 'y' => 0x18, 'z' => 0x19,

      // Commonly mistyped characters (0 => O, 1 => L, 8 => B)
      '0' => 0x0e, '1' => 0x0b, '8' => 0x01
    ];

    $encoded= $arg instanceof SecureString ? $arg->getCharacters() : $arg;

    $buffer= 0;
    $left= 0;
    $this->bytes= '';

    for ($i= 0, $l= strlen($encoded); $i < $l; $i++) {
      $c= $encoded{$i};

      if (isset($table[$c])) {
        $buffer= $buffer << 5 | $table[$c];
        $left+= 5;
        if ($left >= 8) {
          $this->bytes.= chr($buffer >> ($left-= 8));
        }
      } else {
        throw new FormatException(sprintf('Illegal character 0x%02x in input at position %d/%d', ord($c), $i, $l));
      }
    }
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