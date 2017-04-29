<?php declare(strict_types=1);

namespace Link0\Bunq\Tests\Middleware;

use Link0\Bunq\Domain\Keypair\PublicKey;
use Link0\Bunq\Middleware\ResponseSignatureMiddleware;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

final class ResponseSignatureMiddlewareTest extends TestCase
{
    /**
     * @var ResponseSignatureMiddleware
     */
    private $middleware;

    /**
     * @var ResponseInterface|MockInterface
     */
    private $response;

    public function setUp()
    {
        $this->middleware = new ResponseSignatureMiddleware(
            new PublicKey(TEST_PUBLIC_KEY)
        );
        $this->response = Mockery::mock(ResponseInterface::class);
    }

    public function test_that_nothing_is_verified_when_signature_header_missing()
    {
        $this->response
            ->shouldReceive('getHeader')
            ->withArgs(['X-Bunq-Server-Signature'])
            ->andReturn([]);
        $response = ($this->middleware)($this->response);
        $this->assertSame($response, $this->response);
    }

    public function test_that_headers_are_verified_when_signature_present()
    {
        $statusCode = 200;
        $body = '{}';

        $headers = [
            'X-Bunq-Server-Signature' => ['chicken_egg_scenario'],
            'X-Bunq-Include-These-Headers' => ['foo', 'bar'],
            'Foo' => ['Bar'],
        ];

        $signatureData = "$statusCode\n";
        $signatureData .= "X-Bunq-Include-These-Headers: foo\n";
        $signatureData .= "X-Bunq-Include-These-Headers: bar\n";
        $signatureData .= "\n$body";

        openssl_sign($signatureData, $signature, TEST_PRIVATE_KEY, OPENSSL_ALGO_SHA256);

        $this->response
            ->shouldReceive('getHeader')
            ->withArgs(['X-Bunq-Server-Signature'])
            ->andReturn([
                base64_encode($signature)
        ]);

        $this->response
            ->shouldReceive('getStatusCode')
            ->andReturn($statusCode);

        $this->response
            ->shouldReceive('getHeaders')
            ->andReturn($headers);

        $this->response
            ->shouldReceive('getBody')
            ->andReturn($body);

        $response = ($this->middleware)($this->response);
        $this->assertSame($response, $this->response);
    }
}
