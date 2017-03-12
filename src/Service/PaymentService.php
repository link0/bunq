<?php declare(strict_types = 1);

namespace Link0\Bunq\Service;

use Link0\Bunq\Client;
use Link0\Bunq\Domain\Id;
use Link0\Bunq\Domain\Payment;
use Link0\Bunq\Domain\User;

final class PaymentService
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var Id
     */
    private $userId;

    /**
     * @var Id
     */
    private $monetaryAccountId;

    /**
     * @param Client $client
     */
    public function __construct(Client $client, Id $userId, Id $monetaryAccountId)
    {
        $this->client = $client;
        $this->userId = $userId;
        $this->monetaryAccountId = $monetaryAccountId;
    }

    /**
     * @return Payment[]
     */
    public function all(): array
    {
        return $this->client->get(
            'user/' . $this->userId .
            '/monetary-account/' . $this->monetaryAccountId .
            '/payment'
        );
    }
}
