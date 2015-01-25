<?php namespace com\google\authenticator;

/**
 * Counter-based one-time-password (HOTP)
 *
 * @see   http://tools.ietf.org/html/rfc4226
 * @test  xp://com.google.authenticator.unittest.CounterBasedTest
 */
class CounterBased extends Algorithm {

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