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

  /**
   * Generates the token for a given interval
   *
   * @param  int $interval (timestamp div this.interval)
   * @return string
   */
  private function generate($interval) {
    $time= str_pad(pack('N', $interval), 8, "\x00", STR_PAD_LEFT);
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
   * Returns a time-based one-time password at a given time
   *
   * @param  int $time The unix timestamp
   * @return string
   */
  public function at($time) {
    return $this->generate((int)($time / $this->interval));
  }

  /**
   * Returns a time-based one-time password based on the current time
   *
   * @return string
   */
  public function current() {
    return $this->generate((int)(time() / $this->interval));
  }

  /**
   * Returns a time-based one-time password at a given time
   *
   * @param  string $token The token to verify
   * @param  int $time The unix timestamp. If omitted, uses current time
   * @return bool
   */
  public function verify($token, $time= null) {
    return $token === $this->at(null === $time ? time() : $time);
  }
}