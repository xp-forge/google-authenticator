<?php namespace com\google\authenticator\unittest;

use com\google\authenticator\CounterBased;
use com\google\authenticator\SecretString;
use com\google\authenticator\Tolerance;
use security\SecureString;

class CounterBasedTest extends \unittest\TestCase {
  private $secret;

  /**
   * Sets up test by initializing shared secret
   *
   * @return void
   */
  public function setUp() {
    $this->secret= new SecretString(new SecureString('2BX6RYQ4MD5M46KP'));
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

  #[@test]
  public function can_create() {
    new CounterBased($this->secret);
  }

  #[@test, @values('fixtures')]
  public function at($count, $token) {
    $this->assertEquals($token, (new CounterBased($this->secret))->at($count));
  }

  #[@test, @values('fixtures')]
  public function verify_without_tolerance($count, $token) {
    $this->assertTrue((new CounterBased($this->secret))->verify($token, $count, Tolerance::$NONE));
  }

  #[@test, @values('previous_and_next')]
  public function verify_allowing_previous_and_next_is_default($token, $which) {
    $this->assertTrue((new CounterBased($this->secret))->verify($token, 8364950), $which);
  }

  #[@test, @values('previous_and_next')]
  public function verify_allowing_previous_and_next($token, $which) {
    $this->assertTrue((new CounterBased($this->secret))->verify($token, 8364950, Tolerance::$PREVIOUS_AND_NEXT), $which);
  }
}