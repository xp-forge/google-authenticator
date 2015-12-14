<?php namespace com\google\authenticator\unittest;

use com\google\authenticator\TimeBased;
use com\google\authenticator\SecretString;
use com\google\authenticator\Tolerance;
use util\Secret;
use security\SecureString;

class TimeBasedTest extends \unittest\TestCase {
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

  #[@test]
  public function can_create() {
    new TimeBased($this->secret);
  }

  #[@test, @values('fixtures')]
  public function at($time, $token) {
    $this->assertEquals($token, (new TimeBased($this->secret))->at($time));
  }

  #[@test, @values('fixtures')]
  public function verify_without_tolerance($time, $token) {
    $this->assertTrue((new TimeBased($this->secret))->verify($token, $time, Tolerance::$NONE));
  }

  #[@test, @values('previous_and_next')]
  public function verify_allowing_previous_and_next_is_default($token, $which) {
    $this->assertTrue((new TimeBased($this->secret))->verify($token, 250948500), $which);
  }

  #[@test, @values('previous_and_next')]
  public function verify_allowing_previous_and_next($token, $which) {
    $this->assertTrue((new TimeBased($this->secret))->verify($token, 250948500, Tolerance::$PREVIOUS_AND_NEXT), $which);
  }

  #[@test]
  public function current() {
    $t= new TimeBased($this->secret);
    $this->assertEquals($t->at(time()), $t->current());
  }

  #[@test]
  public function previous() {
    $t= new TimeBased($this->secret);
    $this->assertEquals($t->at(time() - $t->interval()), $t->previous());
  }

  #[@test]
  public function next() {
    $t= new TimeBased($this->secret);
    $this->assertEquals($t->at(time() + $t->interval()), $t->next());
  }
}