<?php declare(strict_types = 1);

namespace Link0\Bunq\Domain;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

final class Certificate
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
     * @var string
     */
    private $certificate;

    /**
     * @string
     */
    private function __construct(string $certificate)
    {
        $this->certificate = $certificate;
    }

    /**
     * @param string $certificate
     * @return Certificate
     */
    public static function fromString(string $certificate)
    {
        return new self($certificate);
    }

    /**
     * @param array $value
     * @return Certificate
     */
    public static function fromArray($value)
    {
        $timezone = new DateTimeZone('UTC');
        $cert = new Certificate($value['certificate_chain']);
        $cert->id = Id::fromInteger(intval($value['id']));
        $cert->created = new DateTimeImmutable($value['created'], $timezone);
        $cert->updated = new DateTimeImmutable($value['updated'], $timezone);
        return $cert;
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
     * @return string
     */
    public function certificate(): string
    {
        return $this->certificate;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->certificate;
    }
}
