<?php

namespace Rry\SignatureAuthenticator\Checker;

use Rry\SignatureAuthenticator\Contract\CheckerInterface;
use Rry\SignatureAuthenticator\Exceptions\SignatureValueException;

class SignatureValueChecker implements CheckerInterface
{
    public static function check($toBeChecked, $right)
    {
        if ($toBeChecked !== $right) {
            throw new SignatureValueException('签名错误');
        }

        return true;
    }
}
