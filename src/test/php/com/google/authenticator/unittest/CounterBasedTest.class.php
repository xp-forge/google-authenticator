<?php namespace com\google\authenticator\unittest;

use com\google\authenticator\{CounterBased, Tolerance};
use test\{Assert, Before, Test, Values};
use util\Secret;

class CounterBasedTest {
  private $secret;

  #[Before]
  public function sharedSecret() {
    $this->secret= new Secret('2BX6RYQ4MD5M46KP');
  }

  /** @return var[][] */
  private function fixtures() {
    return [
      [0, '237521'],
      [203, '588403'],
      [8364950, '275135'],
      [47406459, '103874']
    ];
  }

  /** @return var[][] */
  private function previous_and_next() {
    return [
      ['275135', 'correct'],
      ['575521', 'previous'],
      ['043812', 'next']
    ];
  }

  #[Test]
  public function can_create() {
    new CounterBased($this->secret);
  }

  #[Test, Values(from: 'fixtures')]
  public function at($count, $token) {
    Assert::equals($token, (new CounterBased($this->secret))->at($count));
  }

  #[Test, Values(from: 'fixtures')]
  public function verify_without_tolerance($count, $token) {
    Assert::true((new CounterBased($this->secret))->verify($token, $count, Tolerance::$NONE));
  }

  #[Test, Values(from: 'previous_and_next')]
  public function verify_allowing_previous_and_next_is_default($token, $which) {
    Assert::true((new CounterBased($this->secret))->verify($token, 8364950), $which);
  }

  #[Test, Values(from: 'previous_and_next')]
  public function verify_allowing_previous_and_next($token, $which) {
    Assert::true((new CounterBased($this->secret))->verify($token, 8364950, Tolerance::$PREVIOUS_AND_NEXT), $which);
  }

  #[Test]
  public function provisioning_uri() {
    Assert::equals(
      'otpauth://hotp/account-id?secret=2BX6RYQ4MD5M46KP&counter=0',
      (new CounterBased($this->secret))->provisioningUri('account-id')
    );
  }

  #[Test]
  public function provisioning_uri_with_counter() {
    Assert::equals(
      'otpauth://hotp/account-id?secret=2BX6RYQ4MD5M46KP&counter=10',
      (new CounterBased($this->secret))->provisioningUri('account-id', 10)
    );
  }

  #[Test]
  public function provisioning_uri_with_label() {
    Assert::equals(
      'otpauth://hotp/ACME%20Co:account-id?secret=2BX6RYQ4MD5M46KP&counter=0',
      (new CounterBased($this->secret))->provisioningUri(['ACME Co', 'account-id'])
    );
  }

  #[Test]
  public function provisioning_uri_with_digits_other_than_default() {
    Assert::equals(
      'otpauth://hotp/account-id?secret=2BX6RYQ4MD5M46KP&counter=0&digits=8',
      (new CounterBased($this->secret, 8))->provisioningUri('account-id')
    );
  }

  #[Test]
  public function provisioning_uri_with_extra_parameters() {
    Assert::equals(
      'otpauth://hotp/account-id?secret=2BX6RYQ4MD5M46KP&counter=0&issuer=Test',
      (new CounterBased($this->secret))->provisioningUri('account-id', 0, ['issuer' => 'Test'])
    );
  }
}