<?php declare(strict_types=1);

namespace Link0\Bunq\Domain;

use Link0\Bunq\Domain\Keypair\PrivateKey;
use Link0\Bunq\Domain\Keypair\PublicKey;

final class Keypair
{
    /**
     * @var PublicKey
     */
    private $publicKey;
    /**
     * @var PrivateKey
     */
    private $privateKey;

    /**
     * @param PublicKey $publicKey
     * @param PrivateKey $privateKey
     */
    public function __construct(PublicKey $publicKey, PrivateKey $privateKey)
    {
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
    }

    /**
     * @return PrivateKey
     */
    public function privateKey(): PrivateKey
    {
        return $this->privateKey;
    }

    /**
     * @return PublicKey
     */
    public function publicKey(): PublicKey
    {
        return $this->publicKey;
    }

    /**
     * @param string $publicKey
     * @param string $privateKey
     * @return Keypair
     */
    public static function fromStrings(string $publicKey, string $privateKey)
    {
        return new self(
            new PublicKey($publicKey),
            new PrivateKey($privateKey)
        );
    }
}
