<?php namespace com\google\authenticator;

/**
 * Time-based one-time-password (TOTP)
 *
 * @see   http://tools.ietf.org/html/rfc6238
 * @see   php://time
 * @see   php://hash_hmac
 * @test  xp://com.google.authenticator.unittest.TimeBasedTest
 */
class TimeBased extends Algorithm {
  private $interval;

  static function __static() { }

  /**
   * Creates a new time-based one-time-password instance
   *
   * @param  util.Secret|com.google.authenticator.Secret $secret
   * @param  int $interval If omitted, uses 30 seconds
   * @param  int $digits If omitted, defaults to 6
   * @param  string $crypto If omitted, defaults to "sha1"
   */
  public function __construct($secret, $interval= 30, $digits= 6, $crypto= 'sha1') {
    parent::__construct($secret, $digits, $crypto);
    $this->interval= $interval;
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
    return $this->generate((int)($time / $this->interval));
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
   * Verifies a time-based one-time password, optionally using a given tolerance
   *
   * @param  string $token The token to verify
   * @param  int $time The unix timestamp. If omitted, uses current time
   * @param  com.google.authenticator.Tolerance $tolerance If omitted, previous and next is allowed
   * @return bool
   */
  public function verify($token, $time= null, Tolerance $tolerance= null) {
    if (null === $time) $time= time();
    return $this->compare($token, (int)($time/ $this->interval), $tolerance);
  }

  /**
   * Returns provisioning URI
   * 
   * @param  string|string[] $label
   * @param  [:string] $parameters
   * @return string
   */
  public function provisioningUri($label, $parameters= []) {
    return sprintf(
      'otpauth://totp/%s?secret=%s%s%s%s',
      implode(':', array_map('rawurlencode', (array)$label)),
      $this->secret->encoded(),
      30 === $this->interval ? '' : '&period='.$this->interval,
      6 === $this->digits ? '' : '&digits='.$this->digits,
      $parameters ? '&'.http_build_query($parameters) : ''
    );
  }
}