<?php namespace com\google\authenticator;

/**
 * One-time-password (OTP)
 *
 * @see   http://tools.ietf.org/html/rfc4226
 * @see   php://hash_hmac
 * @test  xp://com.google.authenticator.unittest.CounterBasedTest
 */
abstract class Algorithm extends \lang\Object {
  private $secret, $digits, $crypto;

  /**
   * Creates a new one-time-password instance
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

  /**
   * Generates a one-time password for a given counter value.
   *
   * @param  int $count
   * @return string
   */
  protected function generate($count) {
    $message= str_pad(pack('N', $count), 8, "\x00", STR_PAD_LEFT);
    $hash= hash_hmac($this->crypto, $message, $this->secret->bytes(), true);

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
   * Returns a one-time password
   *
   * @param  int $arg
   * @return string
   */
  public abstract function at($arg);

  /**
   * Verifies a one-time password, optionally using a given tolerance
   *
   * @param  string $token The token to verify
   * @param  int $arg
   * @param  com.google.authenticator.Tolerance $tolerance If omitted, previous and next is allowed
   * @return bool
   */
  public abstract function verify($token, $arg, Tolerance $tolerance= null);
}