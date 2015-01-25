<?php namespace com\google\authenticator;

/**
 * Counter-based one-time-password (HOTP)
 *
 * @see   http://tools.ietf.org/html/rfc4226
 * @see   php://hash_hmac
 * @test  xp://com.google.authenticator.unittest.CounterBasedTest
 */
class CounterBased extends \lang\Object {
  private $secret, $digits, $crypto;

  /**
   * Creates a new time-based one-time-password instance
   *
   * @param  com.google.authenticator.Secret $secret
   * @param  int $digits If omitted, defaults to 6
   * @param  string $crypto If omitted, defaults to "sha1"
   */
  public function __construct(Secret $secret, $digits= 6, $crypto= 'sha1') {
    $this->secret= $secret;
    $this->digits= $digits;
    $this->crypto= $crypto;
  }

  /** @return int */
  public function interval() { return $this->interval; }

  /**
   * Returns a counter-based one-time password at a given counter value
   *
   * @param  int $count The counter
   * @return string
   */
  public function at($count) {
    $count= str_pad(pack('N', (int)$count), 8, "\x00", STR_PAD_LEFT);
    $hash= hash_hmac($this->crypto, $count, $this->secret->bytes(), true);

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
   * Returns a counter-based one-time password at a given counter value
   *
   * @param  string $token The token to verify
   * @param  int $count The counter value
   * @param  com.google.authenticator.Tolerance $tolerance If omitted, previous and next is allowed
   * @return bool
   */
  public function verify($token, $count, Tolerance $tolerance= null) {
    if (null === $tolerance) $tolerance= Tolerance::$PREVIOUS_AND_NEXT;

    for ($offset= $tolerance->past(); $offset <= $tolerance->future(); $offset++) {
      if ($token === $this->at($count + $offset)) return true;
    }
    return false;
  }
}