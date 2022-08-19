<?php namespace com\google\authenticator\unittest;

use com\google\authenticator\{SecretString, TimeBased, Tolerance};
use security\SecureString;
use unittest\{Assert, Test, Values};
use util\Secret;

class TimeBasedTest {
  private $secret;

  /**
   * Sets up test by initializing shared secret
   *
   * @return void
   */
  #[Before]
  public function setUp() {

    // FC with newer XP versions, BC with older XP versions
    if (class_exists(Secret::class)) {
      $this->secret= new SecretString(new Secret('2BX6RYQ4MD5M46KP'));
    } else {
      $this->secret= new SecretString(new SecureString('2BX6RYQ4MD5M46KP'));
    }
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

  #[Test, Values('fixtures')]
  public function at($time, $token) {
    Assert::equals($token, (new TimeBased($this->secret))->at($time));
  }

  #[Test, Values('fixtures')]
  public function verify_without_tolerance($time, $token) {
    Assert::true((new TimeBased($this->secret))->verify($token, $time, Tolerance::$NONE));
  }

  #[Test, Values('previous_and_next')]
  public function verify_allowing_previous_and_next_is_default($token, $which) {
    Assert::true((new TimeBased($this->secret))->verify($token, 250948500), $which);
  }

  #[Test, Values('previous_and_next')]
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
}