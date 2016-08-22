<?php

namespace Rry\SignatureAuthenticator\Checker;

use Rry\SignatureAuthenticator\Contract\CheckerInterface;
use Rry\SignatureAuthenticator\Exceptions\SignatureTimestampException;

class TimestampChecker implements CheckerInterface
{
    public static function check($timestamp, $now = null, $expired = 600)
    {
        if (! $now) {
            $now = time();
        }

        if ($now - $timestamp > $expired) {
            throw new SignatureTimestampException('请求时间戳过期');
        }

        return true;
    }
}
