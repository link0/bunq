<?php

namespace Link0\Bunq\Tests\Domain;

use Link0\Bunq\Domain\Id;
use PHPUnit\Framework\TestCase;

final class IdTest extends TestCase
{
    /**
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionMessage Id must be greater or equal to 0
     */
    public function test_that_id_must_not_be_negative()
    {
        Id::fromInteger(-42);
    }

    public function test_that_id_can_be_zero()
    {
        $id = Id::fromInteger(0);
        $this->assertSame(0, $id->id());
    }
    public function test_that_id_can_greater_than_zero()
    {
        $id = Id::fromInteger(42);
        $this->assertSame(42, $id->id());
    }

    public function test_that_id_can_be_casted_to_string()
    {
        $id = Id::fromInteger(42);
        $this->assertEquals('42', (string) $id);
    }
}
