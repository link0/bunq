<?php

namespace Link0\Bunq\Tests\Domain\Keypair;

use Link0\Bunq\Domain\Keypair\PrivateKey;
use PHPUnit\Framework\TestCase;

final class PrivateKeyTest extends TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid PrivateKey format. Please use a shielded format
     */
    public function test_that_private_key_must_be_pem_formatted()
    {
        $key = explode("\n", TEST_PRIVATE_KEY);
        array_shift($key); // -----BEGIN PRIVATE KEY -----
        array_pop($key);   // -----END PRIVATE KEY -----
        new PrivateKey(implode("\n", $key));
    }

    public function test_that_private_key_is_castable_to_string()
    {
        $key = new PrivateKey(TEST_PRIVATE_KEY);
        $this->assertEquals(TEST_PRIVATE_KEY, (string)$key);
    }

    public function test_that_actual_key_is_stripped_from_debug_information()
    {
        $key = new PrivateKey(TEST_PRIVATE_KEY);
        $this->assertContains('***PRIVATE KEY***', print_r($key, true));
    }
}
