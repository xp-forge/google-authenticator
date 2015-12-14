<?php namespace com\google\authenticator\unittest;

use com\google\authenticator\Secrets;

class SecretsTest extends \unittest\TestCase {

  #[@test]
  public function random() {
    $this->assertEquals(10, strlen(Secrets::random()->bytes()));
  }
}