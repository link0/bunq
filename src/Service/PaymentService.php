<?php declare(strict_types = 1);

namespace Link0\Bunq\Service;

use Link0\Bunq\Client;
use Link0\Bunq\Domain\Alias;
use Link0\Bunq\Domain\Id;
use Link0\Bunq\Domain\Payment;
use Link0\Bunq\Domain\User;
use Money\Money;

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

    /**
     * This method performs a payment and returns a payment Id
     *
     * @param Money $money
     * @param Alias $alias
     * @param string $description
     * @param Id[] $attachments,
     * @param string $merchantReference
     * @return Id
     */
    public function pay(
        Money $money,
        Alias $alias,
        string $description,
        array $attachments = [],
        string $merchantReference = ''
    ): Id {
        $data = [
            'money' => [
                'amount' => $money->getAmount(),
                'currency' => $money->getCurrency()->getCode(),
            ],
            'counterparty_alias' => [
                'type' => $alias->type(),
                'value' => $alias->value(),
                'name' => $alias->name(),
            ],
            'description' => $description,
            'merchant_reference' => $merchantReference,
        ];

        foreach ($attachments as $attachment) {
            // Currently an array of Ids
            $data['attachment'][]['id'] = $attachment;
        }

        return $this->client->post(
            'user/' . $this->userId .
            '/monetary-account/' . $this->monetaryAccountId .
            '/payment',
            $data
        )['Id'];
    }
}
