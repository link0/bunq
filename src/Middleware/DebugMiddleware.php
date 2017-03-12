<?php declare(strict_types = 1);

namespace Link0\Bunq\Middleware;

use GuzzleHttp\Middleware;
use GuzzleHttp\Promise\FulfilledPromise;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Be able to debug request and responses
 *
 * Usage:
 *   $handlerStack->push(DebugMiddleware::tap(), 'debug_tap');
 *
 * All requests and responses will now be printed to STDOUT
 *
 */
final class DebugMiddleware
{
    /**
     * @return \Closure
     */
    public static function request()
    {
        return function (RequestInterface $request) {
            echo chr(27) . '[33m' . "REQUEST: " . $request->getMethod() . ' ' . $request->getRequestTarget() . chr(27) . "[0m\n";

            foreach ($request->getHeaders() as $key => $headers) {
                foreach ($headers as $header) {
                    echo "${key}: ${header}\n";
                }
            }

            $body = (string) $request->getBody();
            echo $body;
            $json = @json_decode($body, true);
            if ($json === null) {
                $json = [];
            }
            print_r($json);
        };
    }

    /**
     * @return \Closure
     */
    public static function response()
    {
        return function (RequestInterface $request, $options, FulfilledPromise $responsePromise) {
            $responsePromise->then(function (ResponseInterface $response) {
                echo chr(27) . '[33m' . "RESPONSE: HTTP/" . $response->getProtocolVersion() . ' ' . $response->getStatusCode() . ' ' . $response->getReasonPhrase() . chr(27) . "[0m\n";

                foreach ($response->getHeaders() as $key => $headers) {
                    foreach ($headers as $header) {
                        echo "${key}: ${header}\n";
                    }
                }

                $body = (string) $response->getBody();
                $json = @json_decode($body, true);
                if ($json === null) {
                    $json = [];
                }
                print_r($json);
            });
        };
    }

    /**
     * @return \Closure
     */
    public static function tap()
    {
        return Middleware::tap(
            self::request(),
            self::response()
        );
    }
}
