link0/bunq
==========
[![Latest Stable Version](https://poser.pugx.org/link0/bunq/v/stable.svg)](https://packagist.org/packages/link0/bunq)
[![Total Downloads](https://poser.pugx.org/link0/bunq/downloads.svg)](https://packagist.org/packages/link0/bunq)
[![License](https://poser.pugx.org/link0/bunq/license.svg)](https://packagist.org/packages/link0/bunq)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/link0/bunq/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/link0/bunq/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/link0/bunq/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/link0/bunq/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/link0/bunq/badges/build.png?b=master)](https://scrutinizer-ci.com/g/link0/bunq/build-status/master)

This library implements the [bunq API](https://doc.bunq.com).

Any feedback and testing is very welcome through issues and/or pull requests.

## Basic usage


The following code example does a few things to get you started as fast as possible:

1. Create an InstallationServer
2. Create an DeviceServer
3. Retrieve a session from the SessionServer to be able to use the API

Before you copy/paste this code be sure you have an API key to use. When developing and testing the API you probably don't  want
to use the API key for production. Instead you want to use a developer API key.
A developer API key can be obtained by asking the support desk from bunq itself (using the bunq app). Once you've got that
API key you can fill it in into the `$apiKey` variable.

The function `registerInstallationAndDeviceServer()` is only supposed to be called once. After the first call you can comment this rule out.


```php
<?php

use Link0\Bunq\Client;
use Link0\Bunq\Domain\Keypair;
use Link0\Bunq\Domain\Keypair\PublicKey;
use Link0\Bunq\Environment\Production;
use Link0\Bunq\Environment\Sandbox;
use Link0\Bunq\Service\InstallationService;

require_once('vendor/autoload.php');

/**
 * @param $installationService
 * @param $keypair
 * @param $apiKey
 * @return mixed
 */
function registerInstallationAndDeviceServer(InstallationService $installationService, $keypair, $apiKey)
{
    $installation = $installationService->createInstallation($keypair);

    $installationToken = $installation[1];
    $serverPublicKey = $installation[2];

    // Cache the server public key somewhere
    file_put_contents('server-public-key.txt', $serverPublicKey);

    // Cache the installation token somehere
    file_put_contents('installation-token.txt', $installationToken);

    $installationService->createDeviceServer($installationToken, $apiKey, 'I pasted this from README.md');
}

// openssl genpkey -algorithm RSA -out private.pem -pkeyopt rsa_keygen_bits:2048
// openssl rsa -pubout -in private.pem -out public.pem
$keypair = Keypair::fromStrings(
    file_get_contents('public.pem'),
    file_get_contents('private.pem')
);

// Replace this with what you received from the app
$apiKey = 'your-api-key';

$debugMode = true;

//$environment = new Production($debugMode);
$environment = new Sandbox($debugMode);
$client = new Client($environment, $keypair);

$installationService = new InstallationService($client);

registerInstallationAndDeviceServer($installationService, $keypair, $apiKey);

$installationToken = file_get_contents('installation-token.txt');

$sessionServer = $installationService->createSessionServer($installationToken, $apiKey);

$sessionServerId = $sessionServer[0];
$sessionToken = $sessionServer[1];
$user = $sessionServer[2];

file_put_contents('session-token.txt', $sessionToken);

// After this, you can use the client with all other services as followed
$client = new Client(
    $environment,
    $keypair,
    new PublicKey(file_get_contents('server-public-key.txt')),
    file_get_contents('session-token.txt')
);


```
