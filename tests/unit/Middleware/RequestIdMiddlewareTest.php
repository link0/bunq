<?php declare(strict_types=1);

namespace Link0\Bunq\Tests\Middleware;

use GuzzleHttp\Psr7\Request;
use Link0\Bunq\Middleware\RequestIdMiddleware;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

final class RequestIdMiddlewareTest extends TestCase
{
    /**
     * @const SESSION_TOKEN
     */
    const SESSION_TOKEN = 'some_session_token';

    /**
     * @var Request|MockInterface
     */
    private $request;

    public function setUp()
    {
        $this->request = Mockery::mock(Request::class);
        $this->request->shouldReceive('getMethod')->andReturn('GET');
        $this->request->shouldReceive('getUri')->andReturn('/');
        $this->request->shouldReceive('getBody')->andReturn('');
        $this->middleware = new RequestIdMiddleware(self::SESSION_TOKEN);
    }

    public function test_that_request_id_is_generated_if_not_provided()
    {
        $this->request->shouldReceive('getHeaders')->andReturn([]);
        $request = ($this->middleware)($this->request, []);
        $requestId = $request->getHeader('X-Bunq-Client-Request-Id')[0];
        // UUID's are always 36 characters
        $this->assertEquals(36, strlen($requestId));
    }

    public function test_that_request_id_can_be_overridden()
    {
        $expected = 'foo';
        $this->request->shouldReceive('getHeaders')->andReturn([]);
        $request = ($this->middleware)($this->request, [
            'request-id' => $expected,
        ]);
        $requestId = $request->getHeader('X-Bunq-Client-Request-Id')[0];
        $this->assertSame($expected, $requestId);
    }

    public function test_that_session_token_can_be_overridden()
    {
        $expected = 'foo';
        $this->request->shouldReceive('getHeaders')->andReturn([
            'X-Bunq-Client-Authentication' => [$expected],
        ]);
        $request = ($this->middleware)($this->request, []);
        $authentication = $request->getHeader('X-Bunq-Client-Authentication')[0];
        $this->assertSame($expected, $authentication);
    }
}
