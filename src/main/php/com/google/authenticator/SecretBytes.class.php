<?php namespace com\google\authenticator;

class SecretBytes extends \lang\Object implements Secret {
  private $bytes;

  /** @param string $bytes */
  public function __construct($bytes) {
    $this->bytes= $bytes;
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