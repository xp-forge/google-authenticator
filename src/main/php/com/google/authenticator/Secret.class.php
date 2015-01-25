<?php namespace com\google\authenticator;

interface Secret {

  /**
   * Returns the underlying raw bytes of this secret
   *
   * @return string
   */
  public function bytes();
}