<?php namespace com\google\authenticator\unittest;

use com\google\authenticator\Secret;

class SecretTest extends \unittest\TestCase {

  #[@test]
  public function random() {
    $this->assertEquals(10, strlen(Secret::random()->bytes()));
  }
}