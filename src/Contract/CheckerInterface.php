<?php

namespace Rry\SignatureAuthenticator\Contract;

interface CheckerInterface
{
    public static function check($toBeCheckedValue, $value);
}
