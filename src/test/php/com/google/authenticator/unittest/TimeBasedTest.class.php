<?php namespace com\google\authenticator\unittest;

use com\google\authenticator\TimeBased;
use com\google\authenticator\SecretString;
use security\SecureString;

class TimeBasedTest extends \unittest\TestCase {
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
      [6100, '588403'],
      [250948500, '275135'],
      [1422193781, '103874']
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
  public function verify($time, $token) {
    $this->assertTrue((new TimeBased($this->secret))->verify($token, $time));
  }

  #[@test]
  public function current() {
    $t= new TimeBased($this->secret);
    $this->assertEquals($t->at(time()), $t->current());
  }
}