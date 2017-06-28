<?php declare(strict_types=1);

namespace Link0\Bunq\Tests\Middleware;

use GuzzleHttp\Promise\FulfilledPromise;
use Link0\Bunq\Middleware\DebugMiddleware;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class DebugMiddlewareTest extends TestCase
{
    public function test_that_requests_can_be_represented_as_debug_output()
    {
        $request = Mockery::mock(RequestInterface::class);
        $request->shouldReceive('getMethod')->andReturn('GET');
        $request->shouldReceive('getRequestTarget')->andReturn('/request/target');
        $request->shouldReceive('getHeaders')->andReturn([
            'SomeHeader' => ['SomeValue', 'SomeOtherValue'],
        ]);
        $request->shouldReceive('getBody')->andReturn('{"foo": "bar"}');

        $requestMiddleware = DebugMiddleware::request();

        ob_start();
        $requestMiddleware($request);
        $actual = ob_get_clean();

        // Assert it contains all the desired information
        $this->assertContains('REQUEST', $actual);
        $this->assertContains('GET', $actual);
        $this->assertContains('/request/target', $actual);
        $this->assertContains('SomeHeader: SomeValue', $actual);
        $this->assertContains('SomeHeader: SomeOtherValue', $actual);
        $this->assertContains('{"foo": "bar"}', $actual);
        $this->assertContains('[foo] => bar', $actual);
    }

    public function test_that_responses_can_be_represented_as_debug_output()
    {
        $request = Mockery::mock(RequestInterface::class);
        $response = Mockery::mock(ResponseInterface::class);

        $response->shouldReceive('getProtocolVersion')->andReturn('1.1');
        $response->shouldReceive('getStatusCode')->andReturn('200');
        $response->shouldReceive('getReasonPhrase')->andReturn('OK');
        $response->shouldReceive('getBody')->andReturn('{"foo": "bar"}');
        $response->shouldReceive('getHeaders')->andReturn([
            'Foo' => ['Bar', 'Baz']
        ]);

        $promise = new FulfilledPromise($response);
        $responseMiddleware = DebugMiddleware::response();

        ob_start();
        $responseMiddleware($request, [], $promise)->wait();
        $actual = ob_get_clean();

        $this->assertContains('RESPONSE: HTTP/1.1 200 OK', $actual);
        $this->assertContains('Foo: Bar', $actual);
        $this->assertContains('Foo: Baz', $actual);
        $this->assertContains('[foo] => bar', $actual);
    }
}
