<?php namespace com\google\authenticator\unittest;

use com\google\authenticator\Secrets;
use unittest\{Assert, Test};

class SecretsTest {

  #[Test]
  public function random() {
    Assert::equals(10, strlen(Secrets::random()->bytes()));
  }
}