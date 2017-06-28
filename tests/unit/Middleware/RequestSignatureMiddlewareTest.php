<?php declare(strict_types=1);

namespace Link0\Bunq\Tests\Middleware;

use GuzzleHttp\Psr7\Request;
use Link0\Bunq\Domain\Keypair\PrivateKey;
use Link0\Bunq\Middleware\RequestSignatureMiddleware;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

final class RequestSignatureMiddlewareTest extends TestCase
{
    public function test_request_signing()
    {
        $privateKey = new PrivateKey(TEST_PRIVATE_KEY);
        $middleware = new RequestSignatureMiddleware($privateKey);

        $request = Mockery::mock(RequestInterface::class);

        $headers = [
            'X-Bunq-Testing' => ['Foo']
        ];

        $request
            ->shouldReceive('getHeaders')
            ->withNoArgs()
            ->andReturn($headers)
        ;
        $request
            ->shouldReceive('getMethod')
            ->withNoArgs()
            ->andReturn('GET')
        ;
        $request
            ->shouldReceive('getRequestTarget')
            ->withNoArgs()
            ->andReturn('/')
        ;

        $request->shouldReceive('getUri')->andReturn('foo');

        $request
            ->shouldReceive('getBody')
            ->withNoArgs()
            ->andReturn('{}');

        /** @var Request $response */
        $response = $middleware($request);

        $signatureHeader = $response->getHeader('X-Bunq-Client-Signature');
        $this->assertCount(1, $signatureHeader);
        $this->assertArrayHasKey(0, $signatureHeader);
    }
}
