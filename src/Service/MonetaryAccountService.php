<?php declare(strict_types = 1);

namespace Link0\Bunq\Service;

use Link0\Bunq\Client;
use Link0\Bunq\Domain\Id;
use Link0\Bunq\Domain\MonetaryAccountBank;
use Link0\Bunq\Domain\NotificationFilter;
use Link0\Bunq\Domain\User;
use Link0\Bunq\Domain\UserPerson;
use Money\Money;

final class MonetaryAccountService
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param Id $id
     * @return MonetaryAccountBank
     */
    public function byUserAndId(User $user, Id $id): MonetaryAccountBank
    {
        return $this->client->get('user/' . $user->id() . '/monetary-account/' . $id)[0];
    }

    /**
     * @param User $user
     * @return MonetaryAccountBank[]
     */
    public function listByUser(User $user)
    {
        return $this->client->get('user/' . $user->id() . '/monetary-account');
    }

    /**
     * @param MonetaryAccountBank $monetaryAccount
     * @return void
     */
    public function changeDescription(MonetaryAccountBank $monetaryAccount, string $description)
    {
        $this->updateMonetaryAccount($monetaryAccount, [
            'description' => $description,
        ]);
    }

    public function changeDailyLimit(MonetaryAccountBank $monetaryAccount, Money $dailyLimit)
    {
        $this->updateMonetaryAccount($monetaryAccount, [
            'daily_limit' => [
                'value' => $dailyLimit->getAmount(),
                'currency' => $dailyLimit->getCurrency()->getCode(),
            ],
        ]);
    }

    /**
     * @param MonetaryAccountBank $monetaryAccountBank
     * @return void
     */
    public function closeAccount(MonetaryAccountBank $monetaryAccountBank, string $reason = '')
    {
        $fields = [
            'status' => MonetaryAccountBank::STATUS_CANCELLED,
            'sub_status' => MonetaryAccountBank::SUBSTATUS_REDEMPTION_VOLUNTARY,
        ];

        if ($reason !== '') {
            $fields['reason'] = MonetaryAccountBank::REASON_OTHER;
            $fields['reason_description'] = $reason;
        }

        $this->updateMonetaryAccount($monetaryAccountBank, $fields);
    }

    public function reopenAccount(MonetaryAccountBank $monetaryAccountBank)
    {
        $this->updateMonetaryAccount($monetaryAccountBank, [
            'status' => MonetaryAccountBank::STATUS_PENDING_REOPEN,
            'sub_status' => MonetaryAccountBank::SUBSTATUS_NONE,
        ]);
    }

    /**
     * @param MonetaryAccountBank $monetaryAccountBank
     * @param NotificationFilter $notificationFilter
     * @return void
     */
    public function addNotificationFilter(MonetaryAccountBank $monetaryAccountBank, NotificationFilter $notificationFilter)
    {
        $notificationFilters = $monetaryAccountBank->notificationFilters();
        $notificationFilters[] = $notificationFilter;

        $this->updateNotificationFilters($monetaryAccountBank, $notificationFilters);
    }

    /**
     * @param MonetaryAccountBank $monetaryAccountBank
     * @param NotificationFilter $notificationFilter
     * @return void
     */
    public function removeNotificationFilter(MonetaryAccountBank $monetaryAccountBank, NotificationFilter $notificationFilter)
    {
        $notificationFilters = $monetaryAccountBank->notificationFilters();
        foreach ($notificationFilters as $key => $value) {
            // Remove by reference
            if ($value === $notificationFilter) {
                unset($notificationFilters[$key]);
            }
        }

        $this->updateNotificationFilters($monetaryAccountBank, $notificationFilters);
    }

    private function updateMonetaryAccount(MonetaryAccountBank $monetaryAccountBank, array $newFields)
    {
        $endpoint = 'user/' . $monetaryAccountBank->userId() . '/monetary-account-bank/' . $monetaryAccountBank->id();
        return $this->client->put($endpoint, $newFields);
    }

    /**
     * @param MonetaryAccountBank $monetaryAccountBank
     * @param $notificationFilters
     * @return void
     */
    private function updateNotificationFilters(MonetaryAccountBank $monetaryAccountBank, $notificationFilters)
    {
        $this->updateMonetaryAccount($monetaryAccountBank, [
            'notification_filters' => array_map(function (NotificationFilter $notificationFilter) {
                return $notificationFilter->toArray();
            }, $notificationFilters),
        ]);
    }
}
