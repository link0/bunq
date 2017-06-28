<?php declare(strict_types=1);

namespace Link0\Bunq\Tests\Environment;

use Link0\Bunq\Environment\Sandbox;
use PHPUnit\Framework\TestCase;

final class SandboxTest extends TestCase
{
    public function test_that_sandbox_is_not_in_debug_mode_by_default()
    {
        $this->assertFalse((new Sandbox())->inDebugMode());
    }

    public function test_that_sandbox_can_be_switched_to_debug_mode()
    {
        $debug = true;
        $this->assertTrue((new Sandbox($debug))->inDebugMode());
    }

    public function test_that_version_is_one()
    {
        $this->assertEquals(Sandbox::VERSION, (new Sandbox())->version());
    }

    public function test_that_service_url_equals_constant()
    {
        $this->assertEquals(Sandbox::SERVICE_URL, (new Sandbox())->serviceUrl());
    }

    public function test_that_endpoint_aggregates_other_variables()
    {
        $env = new Sandbox();
        $this->assertEquals($env->serviceUrl() . '/' . $env->version() . '/', $env->endpoint());
    }
}
