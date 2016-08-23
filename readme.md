# 签名加密验证包

[![Build Status](https://travis-ci.org/RryLee/signature-authenticator.svg?branch=master)](https://travis-ci.org/RryLee/signature-authenticator)
[![Latest Stable Version](https://poser.pugx.org/rry/signature-authenticator/v/stable)](https://packagist.org/packages/rry/signature-authenticator)
[![Total Downloads](https://poser.pugx.org/rry/signature-authenticator/downloads)](https://packagist.org/packages/rry/signature-authenticator)
[![License](https://poser.pugx.org/rry/signature-authenticator/license)](https://packagist.org/packages/rry/signature-authenticator)

### 安装

    composer require rry/signature-authenticator

### 使用

```php
$authenticator = Authenticator::make('api_key', 'api_secret');

// 请求接口
$data = [];
echo $authenticator->getValidUrl('http://example.com', $data) . PHP_EOL;

// 获取加密后的参数数组
$data = [];
$authedData = $authenticator->getAuthParameters($data);
```

### signature 的验证方式
签名包含以下三个字段, 首先验证时间戳是否在当前时间 600s 内，其次验证 signature 是否正确，下面是 signature 的验证方式

| 字段名称   |  类型  |  说明  |
| -- | -- | -- |
| stamp   |  int  |  发送请求的时间，UNIX时间戳  |
|  noncestr  |  string  |  随机字符串  |
|  signature  |  string  |  验证签名  |
|  api_key  |  string  |  账户识别码  |

### signature 参数验证步骤如下
1. 设所有接收到的数据为集合M，将集合M内非空参数值（或空数组）的参数按照参数名ASCII码从小到大排序（字典序），使用URL键值对的格式（即key1=value1&key2=value2…）拼接成字符串stringA。
2. 对stringA进行sha256哈希计算，秘钥api_secret，得到signature值

### signature 参数生成步骤如下
1. 设所有需要发送的数据为集合M，在集合M中增加当前时间戳stamp，随机字符串noncestr以及机构的api_key，然后将集合M内非空参数值（或空数组）的参数按照参数名ASCII码从小到大排序（字典序），使用URL键值对的格式（即key1=value1&key2=value2…）拼接成字符串stringA。
2. 对stringA进行sha256哈希计算，秘钥api_secret，得到signature值

>
### 验证和生成签名特别注意以下重要规则：
* 参数名ASCII码从小到大排序（字典序）；
* 如果参数的值为空（或空数组）不参与签名；
* 参数名区分大小写；
* 验证调用返回或微信主动通知签名时，传送的signature参数不参与签名，将生成的签名与该signature参数值作校验。
* 接口可能会增加字段，验证签名时必须支持增加的扩展字段
* 数组先做json encode再参与签名
