<?php

namespace Link0\Bunq;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Link0\Bunq\Domain\Certificate;
use Link0\Bunq\Domain\DeviceServer;
use Link0\Bunq\Domain\Id;
use Link0\Bunq\Domain\Keypair;
use Link0\Bunq\Domain\Keypair\PublicKey;
use Link0\Bunq\Domain\MonetaryAccountBank;
use Link0\Bunq\Domain\Payment;
use Link0\Bunq\Domain\Token;
use Link0\Bunq\Domain\UserCompany;
use Link0\Bunq\Domain\UserPerson;
use Link0\Bunq\Middleware\DebugMiddleware;
use Link0\Bunq\Middleware\RequestIdMiddleware;
use Link0\Bunq\Middleware\RequestSignatureMiddleware;
use Link0\Bunq\Middleware\ResponseSignatureMiddleware;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;

final class Client
{
    /**
     * @var GuzzleClient
     */
    private $guzzle;

    /**
     * @var HandlerStack
     */
    private $handlerStack;

    /**
     * @param Environment $environment
     */
    public function __construct(Environment $environment, Keypair $keypair, PublicKey $serverPublicKey = null, string $sessionToken = '')
    {
        $this->handlerStack = HandlerStack::create();

        $this->handlerStack->push(
            Middleware::mapRequest(new RequestIdMiddleware($sessionToken)),
            'bunq_request_id'
        );

        // TODO: Figure out if we can skip this middleware on POST /installation
        $this->handlerStack->push(
            Middleware::mapRequest(new RequestSignatureMiddleware($keypair->privateKey())),
            'bunq_request_signature'
        );

        // If we have obtained the server's public key, we can verify responses
        if ($serverPublicKey instanceof PublicKey) {
            $this->handlerStack->push(
                Middleware::mapResponse(new ResponseSignatureMiddleware($serverPublicKey))
            );
        }

        if ($environment->inDebugMode()) {
            $this->handlerStack->push(DebugMiddleware::tap(), 'debug_tap');
        }

        $this->guzzle = new GuzzleClient([
            'base_uri' => $environment->endpoint(),
            'handler' => $this->handlerStack,
            'headers' => [
                'User-Agent' => 'Link0 Bunq API Client'
            ]
        ]);
    }

    /**
     * @param string $endpoint
     * @return array
     */
    public function get(string $endpoint, array $headers = []): array
    {
        return $this->processResponse(
            $this->guzzle->request('GET', $endpoint, [
                'headers' => $headers,
            ])
        );
    }

    /**
     * @param string $endpoint
     * @param array $body
     * @param array $headers
     * @return array
     */
    public function post(string $endpoint, array $body, array $headers = []): array
    {
        return $this->processResponse(
            $this->guzzle->request('POST', $endpoint, [
                'json' => $body,
                'headers' => $headers,
            ])
        );
    }

    /**
     * @param string $endpoint
     * @param array $body
     * @param array $headers
     * @return array
     */
    public function put(string $endpoint, array $body, array $headers = []): array
    {
        return $this->processResponse(
            $this->guzzle->request('PUT', $endpoint, [
                'json' => $body,
                'headers' => $headers,
            ])
        );
    }

    /**
     * @param string $endpoint
     * @param array $headers
     * @return void
     */
    public function delete(string $endpoint, array $headers = [])
    {
        $this->guzzle->request('DELETE', $endpoint, [
            'headers' => $headers,
        ]);
    }

    /**
     * @param ResponseInterface $response
     * @return array
     */
    private function processResponse(ResponseInterface $response): array
    {
        $contents = (string) $response->getBody();
        $json = json_decode($contents, true)['Response'];

        // Return empty responses
        if (count($json) === 0) {
            return [];
        }

        foreach ($json as $key => $value) {
            if (is_numeric($key)) {
                // Often only a single associative entry here
                foreach ($value as $type => $struct) {
                    $json[$key] = $this->mapResponse($type, $struct);
                }
            }
        }
        return $json;
    }

    private function mapResponse(string $key, array $value)
    {
        switch ($key) {
            case 'DeviceServer':
                return DeviceServer::fromArray($value);
            case 'MonetaryAccountBank':
                return MonetaryAccountBank::fromArray($value);
            case 'UserPerson':
                return UserPerson::fromArray($value);
            case 'UserCompany':
                return UserCompany::fromArray($value);
            case 'Id':
                return Id::fromInteger($value['id']);
            case 'CertificatePinned':
                return Certificate::fromArray($value);
            case 'Payment':
                return Payment::fromArray($value);
            case 'ServerPublicKey':
                return PublicKey::fromServerPublicKey($value);
            case 'Token':
                return Token::fromArray($value);
            default:
                throw new \Exception("Unknown struct type: " . $key);
        }
    }
}
