<?php declare(strict_types = 1);

namespace Link0\Bunq\Domain;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

final class DeviceServer
{
    /**
     * @var int
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
    private $ip;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $status;

    /**
     * @param array $structure
     * @return DeviceServer
     */
    public static function fromArray(array $structure)
    {
        $timezone = new DateTimeZone('UTC');

        $deviceServer = new static();
        $deviceServer->id = $structure['id'];
        $deviceServer->created = new DateTimeImmutable($structure['created'], $timezone);
        $deviceServer->updated = new DateTimeImmutable($structure['updated'], $timezone);
        $deviceServer->ip = $structure['ip'];
        $deviceServer->description = $structure['description'];
        $deviceServer->status = $structure['status'];
        return $deviceServer;
    }

    /**
     * @return int
     */
    public function id(): int
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
    public function ip(): string
    {
        return $this->ip;
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function status(): string
    {
        return $this->status;
    }
}
