<?php

namespace Link0\Bunq\Tests\Domain\Keypair;

use Link0\Bunq\Domain\Keypair\PublicKey;
use PHPUnit\Framework\TestCase;

final class PublicKeyTest extends TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid PublicKey format. Please use a shielded format
     */
    public function test_that_private_key_must_be_pem_formatted()
    {
        $key = explode("\n", TEST_PUBLIC_KEY);
        array_shift($key); // -----BEGIN PUBLIC KEY -----
        array_pop($key);   // -----END PUBLIC KEY -----
        new PublicKey(implode("\n", $key));
    }

    public function test_that_private_key_is_castable_to_string()
    {
        $key = new PublicKey(TEST_PUBLIC_KEY);
        $this->assertSame(TEST_PUBLIC_KEY, (string)$key);
    }

    public function test_that_public_key_can_be_made_from_server_response()
    {
        $key = PublicKey::fromServerPublicKey([
            'server_public_key' => TEST_PUBLIC_KEY
        ]);
        $this->assertEquals(TEST_PUBLIC_KEY, $key);
    }
}
