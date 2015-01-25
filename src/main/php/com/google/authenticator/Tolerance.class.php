<?php namespace com\google\authenticator;

class Tolerance extends \lang\Object {
  public static $NONE, $PREVIOUS_AND_NEXT;
  private $past, $future;

  static function __static() {
    self::$NONE= new self(0, 0);
    self::$PREVIOUS_AND_NEXT= new self(-1, +1);
  }

  /**
   * Creates a new tolerance instance
   *
   * @param  int $past How many previous tokens to allow (< 0)
   * @param  int $future How many next tokens to allow (> 0)
   */
  public function __construct($past, $future) {
    $this->past= $past > 0 ? -$past : $past;
    $this->future= $future < 0 ? -$future : $future;
  }

  /** @return int */
  public function past() { return $this->past; }

  /** @return int */
  public function future() { return $this->future; }

  /**
   * Creates a string representation of this tolerance instance.
   *
   * @return string
   */
  public function toString() {
    if ($this->past === $this->future) {
      return $this->getClassName().'('.$this->future.')';
    } else {
      return $this->getClassName().'(['.$this->past.'..+'.$this->future.'])';
    }
  }
}