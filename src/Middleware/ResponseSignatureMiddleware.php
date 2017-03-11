<?php declare(strict_types=1);

namespace Link0\Bunq\Middleware;

use Link0\Bunq\Domain\Keypair\PublicKey;
use Psr\Http\Message\ResponseInterface;

final class ResponseSignatureMiddleware
{
    const SIGNATURE_ALGORITHM = OPENSSL_ALGO_SHA256;

    /**
     * @var PublicKey
     */
    private $publicKey;

    /**
     * @param PublicKey $serverPublicKey
     */
    public function __construct(PublicKey $serverPublicKey)
    {
        $this->publicKey = $serverPublicKey;
    }

    /**
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws \Exception
     */
    public function __invoke(ResponseInterface $response)
    {
        $header = $response->getHeader('X-Bunq-Server-Signature');

        if (isset($header[0])) {
            $serverSignature = $header[0];

            $signatureData = $response->getStatusCode();

            $headers = $response->getHeaders();
            ksort($headers);

            foreach ($headers as $header => $values) {
                // Skip the server signature itself
                if ($header === 'X-Bunq-Server-Signature') {
                    continue;
                }

                // Skip all headers that are not X-Bunq-
                if (substr($header, 0, 7) !== 'X-Bunq-') {
                    continue;
                }

                // Add all header data to verify signature
                foreach ($values as $value) {
                    $signatureData .= PHP_EOL . $header . ': ' . $value;
                }
            }

            $signatureData .= "\n\n";
            $signatureData .= (string) $response->getBody();

            $rawSignature = base64_decode($serverSignature);
            $verify = openssl_verify($signatureData, $rawSignature, $this->publicKey, self::SIGNATURE_ALGORITHM);
            if ($verify !== 1) {
                throw new \Exception("Server signature does not match response");
            }
        }

        return $response;
    }
}
