<?php declare(strict_types=1);

namespace Link0\Bunq\Domain\Keypair;

use \InvalidArgumentException;

final class PrivateKey
{
    /**
     * @var string
     */
    private $key;

    /**
     * @param string $privateKey
     */
    public function __construct(string $privateKey)
    {
        if (!preg_match('/^\-\-\-\-\-BEGIN PRIVATE KEY\-\-\-\-\-[\S\n]+\-\-\-\-\-END PRIVATE KEY\-\-\-\-\-$/', $privateKey)) {
            throw new InvalidArgumentException("Invalid PrivateKey format. Please use a shielded format");
        }

        $this->key = $privateKey;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->key;
    }

    /**
     * @return array
     */
    public function __debugInfo()
    {
        // Hide the actual key from backtraces
        return ['key' => '***PRIVATE KEY***'];
    }
}
