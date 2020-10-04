<?php namespace com\google\authenticator\unittest;

use com\google\authenticator\Secrets;
use unittest\Test;

class SecretsTest extends \unittest\TestCase {

  #[Test]
  public function random() {
    $this->assertEquals(10, strlen(Secrets::random()->bytes()));
  }
}