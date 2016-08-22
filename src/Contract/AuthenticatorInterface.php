<?php

namespace Rry\SignatureAuthenticator\Contract;

interface AuthenticatorInterface
{
    public static function make($api_key, $api_secret);

    public function attempt(array $parameters);

    public function getSignatureValue(array $parameters);

    public function checkTimestamp($timestamp);

    public function checkSignatureValue(array $parameters);

    public function getAuthParameters(array $parameters = []);

    public function getValidUrl($url, array $parameters = []);
}
