<?php declare(strict_types=1);

namespace Link0\Bunq\Domain\Keypair;

use \InvalidArgumentException;

final class PublicKey
{
    /**
     * @var string
     */
    private $key;

    /**
     * @param string $publicKey
     */
    public function __construct(string $publicKey)
    {
        if (!preg_match('/^\-\-\-\-\-BEGIN PUBLIC KEY\-\-\-\-\-[\S\n]+\-\-\-\-\-END PUBLIC KEY\-\-\-\-\-$/', $publicKey)) {
            throw new InvalidArgumentException("Invalid PublicKey format. Please use a shielded format");
        }

        $this->key = $publicKey;
    }

    /**
     * @param array $serverPublicKey
     * @return PublicKey
     */
    public static function fromServerPublicKey(array $serverPublicKey)
    {
        return new self($serverPublicKey['server_public_key']);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->key;
    }
}
