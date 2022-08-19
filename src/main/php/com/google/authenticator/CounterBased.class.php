<?php namespace com\google\authenticator;

/**
 * Counter-based one-time-password (HOTP)
 *
 * @see   http://tools.ietf.org/html/rfc4226
 * @test  xp://com.google.authenticator.unittest.CounterBasedTest
 */
class CounterBased extends Algorithm {

  static function __static() { }

  /**
   * Returns a counter-based one-time password at a given counter value
   *
   * @param  int $count The counter
   * @return string
   */
  public function at($count) {
    return $this->generate((int)$count);
  }

  /**
   * Verifies a counter-based one-time password, optionally using a given tolerance
   *
   * @param  string $token The token to verify
   * @param  int $count The counter value
   * @param  com.google.authenticator.Tolerance $tolerance If omitted, previous and next is allowed
   * @return bool
   */
  public function verify($token, $count, Tolerance $tolerance= null) {
    return $this->compare($token, (int)$count, $tolerance);
  }

  /**
   * Returns provisioning URI
   * 
   * @param  string $account
   * @param  int $counter
   * @return string
   */
  public function provisioningUri($account, $counter= 0) {
    return sprintf('otpauth://hotp/%s?secret=%s&counter=%d', $account, $this->secret->encoded(), $counter);
  }
}