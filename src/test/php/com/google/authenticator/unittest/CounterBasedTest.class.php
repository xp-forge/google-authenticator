<?php namespace com\google\authenticator\unittest;

use com\google\authenticator\{CounterBased, SecretString, Tolerance};
use security\SecureString;
use unittest\{Test, Values};
use util\Secret;

class CounterBasedTest extends \unittest\TestCase {
  private $secret;

  /**
   * Sets up test by initializing shared secret
   *
   * @return void
   */
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

  #[Test, Values('fixtures')]
  public function at($count, $token) {
    $this->assertEquals($token, (new CounterBased($this->secret))->at($count));
  }

  #[Test, Values('fixtures')]
  public function verify_without_tolerance($count, $token) {
    $this->assertTrue((new CounterBased($this->secret))->verify($token, $count, Tolerance::$NONE));
  }

  #[Test, Values('previous_and_next')]
  public function verify_allowing_previous_and_next_is_default($token, $which) {
    $this->assertTrue((new CounterBased($this->secret))->verify($token, 8364950), $which);
  }

  #[Test, Values('previous_and_next')]
  public function verify_allowing_previous_and_next($token, $which) {
    $this->assertTrue((new CounterBased($this->secret))->verify($token, 8364950, Tolerance::$PREVIOUS_AND_NEXT), $which);
  }
}