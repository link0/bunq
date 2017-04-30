<?php declare(strict_types=1);

namespace Link0\Bunq\Tests\Middleware;

use Link0\Bunq\Client;
use Link0\Bunq\ClientInterface;
use Link0\Bunq\Domain\Id;
use Link0\Bunq\Domain\UserPerson;
use Link0\Bunq\Service\UserService;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

final class UserServiceTest extends TestCase
{
    /**
     * @var Client|MockInterface
     */
    private $client;

    /**
     * @var UserService
     */
    private $service;

    public function setUp()
    {
        $this->client = Mockery::mock(ClientInterface::class);
        $this->service = new UserService($this->client);
    }

    public function test_that_list_users_calls_client()
    {
        $users = [];
        $this->client->shouldReceive('get')->withArgs(['user'])->andReturn($users);
        $actualUsers = $this->service->listUsers();
        $this->assertSame($users, $actualUsers);
    }

    public function test_that_user_fetchable_by_id()
    {
        $userId = Id::fromInteger(42);
        $user = null;
        $this->client->shouldReceive('get')->withArgs(['user/' . $userId])->andReturn($user);
        $actualUser = $this->service->userById($userId);
        $this->assertSame($user, $actualUser);
    }
}
