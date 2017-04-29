<?php declare(strict_types=1);

namespace Link0\Bunq\Tests\Environment;

use Link0\Bunq\Environment\Production;
use PHPUnit\Framework\TestCase;

final class ProductionTest extends TestCase
{
    public function test_that_production_is_not_in_debug_mode_by_default()
    {
        $this->assertFalse((new Production())->inDebugMode());
    }

    public function test_that_production_can_be_switched_to_debug_mode()
    {
        $debug = true;
        $this->assertTrue((new Production($debug))->inDebugMode());
    }

    public function test_that_version_is_one()
    {
        $this->assertEquals(Production::VERSION, (new Production())->version());
    }

    public function test_that_service_url_equals_constant()
    {
        $this->assertEquals(Production::SERVICE_URL, (new Production())->serviceUrl());
    }

    public function test_that_endpoint_aggregates_other_variables()
    {
        $env = new Production();
        $this->assertEquals($env->serviceUrl() . '/' . $env->version() . '/', $env->endpoint());
    }
}
