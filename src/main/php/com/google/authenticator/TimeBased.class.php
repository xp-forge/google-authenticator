<?php namespace com\google\authenticator;

/**
 * Time-based one-time-password (TOTP)
 *
 * @see   http://tools.ietf.org/html/rfc6238
 * @see   php://time
 * @see   php://hash_hmac
 * @test  xp://com.google.authenticator.unittest.TimeBasedTest
 */
class TimeBased extends \lang\Object {
  private $secret, $interval, $digits, $crypto;

  /**
   * Creates a new time-based one-time-password instance
   *
   * @param  com.google.authenticator.Secret $secret
   * @param  int $interval If omitted, uses 30 seconds
   * @param  int $digits If omitted, defaults to 6
   * @param  string $crypto If omitted, defaults to "sha1"
   */
  public function __construct(Secret $secret, $interval= 30, $digits= 6, $crypto= 'sha1') {
    $this->secret= $secret;
    $this->interval= $interval;
    $this->digits= $digits;
    $this->crypto= $crypto;
  }

  /** @return int */
  public function interval() { return $this->interval; }

  /**
   * Returns a time-based one-time password at a given time
   *
   * @param  int $time The unix timestamp
   * @return string
   */
  public function at($time) {
    $time= str_pad(pack('N', (int)($time / $this->interval)), 8, "\x00", STR_PAD_LEFT);
    $hash= hash_hmac($this->crypto, $time, $this->secret->bytes(), true);

    $offset= ord($hash{strlen($hash) - 1}) & 0xf;
    $binary=
      ((ord($hash{$offset}) & 0x7f) << 24) |
      ((ord($hash{$offset + 1}) & 0xff) << 16) |
      ((ord($hash{$offset + 2}) & 0xff) << 8) |
      ((ord($hash{$offset + 3}) & 0xff))
    ;

    return str_pad($binary % pow(10, $this->digits), $this->digits, '0', STR_PAD_LEFT);
  }

  /**
   * Returns a time-based one-time password based on the current time
   *
   * @return string
   */
  public function current() {
    return $this->at(time());
  }

  /**
   * Returns the previous token based on the current time
   *
   * @return string
   */
  public function previous() {
    return $this->at(time() - $this->interval);
  }

  /**
   * Returns the next token based on the current time
   *
   * @return string
   */
  public function next() {
    return $this->at(time() + $this->interval);
  }

  /**
   * Returns a time-based one-time password at a given time
   *
   * @param  string $token The token to verify
   * @param  com.google.authenticator.Tolerance $tolerance If omitted, previous and next is allowed
   * @param  int $time The unix timestamp. If omitted, uses current time
   * @return bool
   */
  public function verify($token, Tolerance $tolerance= null, $time= null) {
    if (null === $tolerance) $tolerance= Tolerance::$PREVIOUS_AND_NEXT;
    if (null === $time) $time= time();

    for ($offset= $tolerance->past(); $offset <= $tolerance->future(); $offset++) {
      if ($token === $this->at($time + $offset * $this->interval)) return true;
    }
    return false;
  }
}