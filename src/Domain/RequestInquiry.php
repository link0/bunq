<?php
declare(strict_types = 1);

namespace Link0\Bunq\Domain;

use DateTimeInterface;
use DateTimeZone;
use Money\Currency;
use Money\Money;

final class RequestInquiry
{
    /**
     * @var Id
     */
    private $id;

    /**
     * @var DateTimeInterface
     */
    private $created;

    /**
     * @var DateTimeInterface
     */
    private $updated;

    /**
     * @var DateTimeInterface
     */
    private $time_responded;

    /**
     * @var DateTimeInterface
     */
    private $time_expiry;

    /**
     * @var Money
     */
    private $amount_inquired;

    /**
     * @var Money
     */
    private $amount_responded;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $description;

    /**
     * @var LabelMonetaryAccount
     */
    private $user_alias_created;

    /**
     * @var LabelMonetaryAccount
     */
    private $user_alias_revoked;

    /**
     * @var LabelMonetaryAccount;
     */
    private $counterparty_alias;


    /**
     * Private constructor
     */
    private function __construct(array $requestInquiry)
    {
        $timezone = new DateTimeZone('UTC');

        $this->id = Id::fromInteger(intval($requestInquiry['id']));
        $this->description = $requestInquiry['description'];
        $this->status = $requestInquiry['status'];

        $this->created = new \DateTimeImmutable($requestInquiry['created'], $timezone);
        $this->updated = new \DateTimeImmutable($requestInquiry['updated'], $timezone);
        $this->time_responded = is_null($requestInquiry['time_responded']) ? null : new \DateTimeImmutable($requestInquiry['time_responded'], $timezone);
        $this->time_expiry = is_null($requestInquiry['time_expiry']) ? null : new \DateTimeImmutable($requestInquiry['time_expiry'], $timezone);

        $this->amount_inquired = new Money(
            $requestInquiry['amount_inquired']['value'] * 100, // cents
            new Currency($requestInquiry['amount_inquired']['currency'])
        );

        $this->amount_responded = is_null($requestInquiry['amount_responded']) ? null : new Money(
            $requestInquiry['amount_responded']['value'] * 100, // cents
            new Currency($requestInquiry['amount_responded']['currency'])
        );

        $this->user_alias_created = LabelMonetaryAccount::fromArray($requestInquiry['user_alias_created']);
        $this->user_alias_revoked = is_null($requestInquiry['user_alias_revoked']) ? null : LabelMonetaryAccount::fromArray($requestInquiry['user_alias_revoked']);
        $this->counterparty_alias = LabelMonetaryAccount::fromArray($requestInquiry['counterparty_alias']);
    }

    /**
     * @param array $requestInquiry
     * @return RequestInquiry
     */
    public static function fromArray(array $requestInquiry)
    {
        return new self($requestInquiry);
    }

    /**
    * @return Id
    */
    public function id(): Id
    {
        return $this->id;
    }

    /**
     * @return DateTimeInterface
     */
    public function created(): DateTimeInterface
    {
        return $this->created;
    }

    /**
     * @return DateTimeInterface
     */
    public function updated(): DateTimeInterface
    {
        return $this->updated;
    }

    /**
     * @return DateTimeInterface
     */
    public function time_responded(): DateTimeInterface
    {
        return $this->time_responded;
    }

    /**
     * @return DateTimeInterface
     */
    public function time_expiry(): DateTimeInterface
    {
        return $this->time_expiry;
    }

    /**
     * @return Money
     */
    public function amount_inquired(): Money
    {
        return $this->amount_inquired;
    }

    /**
     * @return Money
     */
    public function amount_responded(): Money
    {
        return $this->amount_responded;
    }

    /**
     * @return string
     */
    public function status(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return $this->description;
    }

    /**
     * @return LabelMonetaryAccount
     */
    public function user_alias_created(): LabelMonetaryAccount
    {
        return $this->user_alias_created;
    }

    /**
     * @return LabelMonetaryAccount
     */
    public function user_alias_revoked(): LabelMonetaryAccount
    {
        return $this->user_alias_revoked;
    }

    /**
     * @return LabelMonetaryAccount
     */
    public function counterparty_alias(): LabelMonetaryAccount
    {
        return $this->counterparty_alias;
    }
}