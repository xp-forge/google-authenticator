<?php namespace com\google\authenticator\unittest;

use com\google\authenticator\{TimeBased, Tolerance};
use test\{Assert, Before, Test, Values};
use util\Secret;

class TimeBasedTest {
  private $secret;

  #[Before]
  public function sharedSecret() {
    $this->secret= new Secret('2BX6RYQ4MD5M46KP');
  }

  /** @return var[][] */
  private function fixtures() {
    return [
      [0, '237521'],
      [6100, '588403'],
      [250948500, '275135'],
      [1422193781, '103874']
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
    new TimeBased($this->secret);
  }

  #[Test, Values(from: 'fixtures')]
  public function at($time, $token) {
    Assert::equals($token, (new TimeBased($this->secret))->at($time));
  }

  #[Test, Values(from: 'fixtures')]
  public function verify_without_tolerance($time, $token) {
    Assert::true((new TimeBased($this->secret))->verify($token, $time, Tolerance::$NONE));
  }

  #[Test, Values(from: 'previous_and_next')]
  public function verify_allowing_previous_and_next_is_default($token, $which) {
    Assert::true((new TimeBased($this->secret))->verify($token, 250948500), $which);
  }

  #[Test, Values(from: 'previous_and_next')]
  public function verify_allowing_previous_and_next($token, $which) {
    Assert::true((new TimeBased($this->secret))->verify($token, 250948500, Tolerance::$PREVIOUS_AND_NEXT), $which);
  }

  #[Test]
  public function current() {
    $t= new TimeBased($this->secret);
    Assert::equals($t->at(time()), $t->current());
  }

  #[Test]
  public function previous() {
    $t= new TimeBased($this->secret);
    Assert::equals($t->at(time() - $t->interval()), $t->previous());
  }

  #[Test]
  public function next() {
    $t= new TimeBased($this->secret);
    Assert::equals($t->at(time() + $t->interval()), $t->next());
  }

  #[Test]
  public function provisioning_uri() {
    Assert::equals(
      'otpauth://totp/account-id?secret=2BX6RYQ4MD5M46KP',
      (new TimeBased($this->secret))->provisioningUri('account-id')
    );
  }

  #[Test]
  public function provisioning_uri_with_label() {
    Assert::equals(
      'otpauth://totp/ACME%20Co:account-id?secret=2BX6RYQ4MD5M46KP',
      (new TimeBased($this->secret))->provisioningUri(['ACME Co', 'account-id'])
    );
  }

  #[Test]
  public function provisioning_uri_with_digits_other_than_default() {
    Assert::equals(
      'otpauth://totp/account-id?secret=2BX6RYQ4MD5M46KP&digits=8',
      (new TimeBased($this->secret, 30, 8))->provisioningUri('account-id')
    );
  }

  #[Test, Values([15, 60])]
  public function provisioning_uri_with_interval_other_than_default($interval) {
    Assert::equals(
      'otpauth://totp/account-id?secret=2BX6RYQ4MD5M46KP&period='.$interval,
      (new TimeBased($this->secret, $interval))->provisioningUri('account-id')
    );
  }

  #[Test]
  public function provisioning_uri_with_extra_parameters() {
    Assert::equals(
      'otpauth://totp/account-id?secret=2BX6RYQ4MD5M46KP&issuer=Test',
      (new TimeBased($this->secret))->provisioningUri('account-id', ['issuer' => 'Test'])
    );
  }
}