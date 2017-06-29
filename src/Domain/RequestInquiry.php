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
    private $timeResponded;

    /**
     * @var DateTimeInterface
     */
    private $timeExpiry;

    /**
     * @var Money
     */
    private $amountInquired;

    /**
     * @var Money
     */
    private $amountResponded;

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
    private $userAliasCreated;

    /**
     * @var LabelMonetaryAccount
     */
    private $userAliasRevoked;

    /**
     * @var LabelMonetaryAccount;
     */
    private $counterpartyAlias;


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
        $this->timeResponded = is_null($requestInquiry['timeResponded']) ? null : new \DateTimeImmutable($requestInquiry['timeResponded'], $timezone);
        $this->timeExpiry = is_null($requestInquiry['timeExpiry']) ? null : new \DateTimeImmutable($requestInquiry['timeExpiry'], $timezone);

        $this->amountInquired = new Money(
            $requestInquiry['amountInquired']['value'] * 100, // cents
            new Currency($requestInquiry['amountInquired']['currency'])
        );

        $this->amountResponded = is_null($requestInquiry['amountResponded']) ? null : new Money(
            $requestInquiry['amountResponded']['value'] * 100, // cents
            new Currency($requestInquiry['amountResponded']['currency'])
        );

        $this->userAliasCreated = LabelMonetaryAccount::fromArray($requestInquiry['userAliasCreated']);
        $this->userAliasRevoked = is_null($requestInquiry['userAliasRevoked']) ? null : LabelMonetaryAccount::fromArray($requestInquiry['userAliasRevoked']);
        $this->counterpartyAlias = LabelMonetaryAccount::fromArray($requestInquiry['counterpartyAlias']);
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
    public function timeResponded(): DateTimeInterface
    {
        return $this->timeResponded;
    }

    /**
     * @return DateTimeInterface
     */
    public function timeExpiry(): DateTimeInterface
    {
        return $this->timeExpiry;
    }

    /**
     * @return Money
     */
    public function amountInquired(): Money
    {
        return $this->amountInquired;
    }

    /**
     * @return Money
     */
    public function amountResponded(): Money
    {
        return $this->amountResponded;
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
    public function userAliasCreated(): LabelMonetaryAccount
    {
        return $this->userAliasCreated;
    }

    /**
     * @return LabelMonetaryAccount
     */
    public function userAliasRevoked(): LabelMonetaryAccount
    {
        return $this->userAliasRevoked;
    }

    /**
     * @return LabelMonetaryAccount
     */
    public function counterpartyAlias(): LabelMonetaryAccount
    {
        return $this->counterpartyAlias;
    }
}
