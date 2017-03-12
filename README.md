link0/bunq
==========
[![Latest Stable Version](https://poser.pugx.org/link0/bunq/v/stable.svg)](https://packagist.org/packages/link0/bunq)
[![Total Downloads](https://poser.pugx.org/link0/bunq/downloads.svg)](https://packagist.org/packages/link0/bunq)
[![License](https://poser.pugx.org/link0/bunq/license.svg)](https://packagist.org/packages/link0/bunq)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/link0/bunq/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/link0/bunq/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/link0/bunq/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/link0/bunq/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/link0/bunq/badges/build.png?b=master)](https://scrutinizer-ci.com/g/link0/bunq/build-status/master)

This library implements the bunq API.

Any feedback and testing is very much welcome though issues and/or pull requests.

## Basic usage

```php
<?php

use Link0\Bunq\Client;
use Link0\Bunq\Domain\Keypair;
use Link0\Bunq\Domain\Keypair\PublicKey;
use Link0\Bunq\Environment\Sandbox;
use Link0\Bunq\Service\InstallationService;

require_once('vendor/autoload.php');

// openssl genpkey -algorithm RSA -out private.pem -pkeyopt rsa_keygen_bits:2048
// openssl rsa -pubout -in private.pem -out public.pem
$keypair = Keypair::fromStrings(
    file_get_contents('public.pem'),
    file_get_contents('private.pem')
);

// Replace this with what you received from the app
$apiKey = 'foobarbaz';

$debugMode = true;
$environment = new Sandbox($debugmode);
$client = new Client($environment, $keypair);

$installationService = new InstallationService($client);
$installation = $installationService->createInstallation($keypair);

// Useful information
print_r($installation);

$installationId = $installation[0];
$installationToken = $installation[1];
$serverPublicKey = $installation[2];

// Cache the server public key somewhere
file_put_contents('server-public-key.txt', $serverPublicKey);

// Cache the installation token somehere
file_put_contents('installation-token.txt', $installationToken);

$deviceServerId = $installationService->createDeviceServer($installationToken, $apiKey, 'I pasted this from README.md');
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
    file_get_contents($sessionToken);
);

```
