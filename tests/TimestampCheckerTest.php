<?php

use Rry\SignatureAuthenticator\Checker\TimestampChecker;
use Rry\SignatureAuthenticator\Checker\SignatureKeyChecker;

class TimestampCheckerTest extends PHPUnit_Framework_TestCase
{
    public function test_it_pass_when_time_pass_in_five_minutes_once()
    {
        $this->assertTrue(TimestampChecker::check(time() - 600));
    }

    public function test_it_pass_when_time_pass_in_five_minutes_twice()
    {
        $this->assertTrue(TimestampChecker::check(time() - 600), time() - 10);
    }

    /**
     * @expectedException \Rry\SignatureAuthenticator\Exceptions\SignatureTimestampException
     */
    public function test_it_throw_timestamp_exception_once()
    {
        TimestampChecker::check(time() - 601);
    }

    /**
     * @expectedException \Rry\SignatureAuthenticator\Exceptions\SignatureTimestampException
     */
    public function test_it_throw_timestamp_exception_twice()
    {
        TimestampChecker::check(time() - 600, time() + 1);
    }
}
