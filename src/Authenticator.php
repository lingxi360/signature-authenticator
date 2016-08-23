<?php

namespace Rry\SignatureAuthenticator;

use Exception;
use Rry\SignatureAuthenticator\Checker\SignatureValueChecker;
use Rry\SignatureAuthenticator\Checker\TimestampChecker;
use Rry\SignatureAuthenticator\Contract\AuthenticatorInterface;

class Authenticator implements AuthenticatorInterface
{
    protected $api_key;

    protected $api_secret;

    public function __construct($api_key = '', $api_secret = '')
    {
        $this->setApiKeyAndSecret($api_key, $api_secret);
    }

    public static function make($api_key, $api_secret)
    {
        return new self($api_key, $api_secret);
    }

    public function attempt(array $parameters)
    {
        $this->checkApiKeySecret();

        return $this->checkTimestamp($parameters['stamp'])
                    ->checkSignatureValue($parameters);
    }

    public function checkTimestamp($timestamp)
    {
        if (TimestampChecker::check($timestamp)) {
            return $this;
        }
    }

    public function checkSignatureValue(array $parameters)
    {
        return SignatureValueChecker::check($parameters['signature'], $this->getSignatureValue($parameters));
    }

    public function getSignatureValue(array $parameters)
    {
        $parametersString = urldecode(http_build_query($this->handleAllSignatureParamaters($parameters)));

        return hash_hmac('sha256', $parametersString, $this->api_secret);
    }

    public function getAuthParameters(array $parameters = [])
    {
        $parameters['stamp'] = time();
        $parameters['noncestr'] = str_random(16);
        $parameters['api_key'] = $this->api_key;
        $parameters['signature'] = $this->getSignatureValue($parameters);

        return $parameters;
    }

    public function getValidUrl($url, array $parameters = [])
    {
        ! ends_with($url, '?') and $url .= '?';

        return $url . http_build_query($this->getAuthParameters($parameters));
    }

    protected function handleAllSignatureParamaters($params)
    {
        $params = collect($params)
            ->filter(function ($item, $key) {
                return ! in_array($key, ['signature']) && $item !== ''; // 值为空的参数不参与签名
            })
            ->toArray();

        $params = array_change_key_case($params, CASE_LOWER);
        ksort($params, SORT_STRING);

        return $params;
    }

    protected function checkApiKeySecret()
    {
        if ($this->api_key === '' || $this->api_secret === '') {
            throw new Exception('api_key or api_secret must be not null.');
        }
    }

    protected function setApiKeyAndSecret($api_key, $api_secret)
    {
        $this->api_key = $api_key;

        $this->api_secret = $api_secret;
    }

    public function __set($key, $value)
    {
        $this->{$key} = $value;

        return $this;
    }
}
