<?php declare(strict_types=1);

namespace Link0\Bunq\Middleware;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Ramsey\Uuid\Uuid;

final class RequestIdMiddleware
{
    private $sessionToken;
    public function __construct($sessionToken)
    {
        $this->sessionToken = $sessionToken;
    }

    /**
     * @param RequestInterface $request
     * @param array $options
     * @return Request
     */
    public function __invoke(RequestInterface $request, array $options = [])
    {
        $requestId = Uuid::uuid4()->toString();
        if (isset($options['request-id'])) {
            $requestId = $options['request-id'];
        }

        $headers = $request->getHeaders();
        $headers['X-Bunq-Client-Request-Id'][] = $requestId;
        $headers['Cache-Control'][] = 'no-cache';
        $headers['X-Bunq-Geolocation'][] = '52.3 4.89 12 100 NL';
        $headers['X-Bunq-Language'][] = 'en_US';
        $headers['X-Bunq-Region'][] = 'en_US';

        // Use the session token if not overridden with installation token
        if (!isset($headers['X-Bunq-Client-Authentication'])) {
            $headers['X-Bunq-Client-Authentication'] = $this->sessionToken;
        }

        return new Request(
            $request->getMethod(),
            $request->getUri(),
            $headers,
            $request->getBody()
        );
    }
}
