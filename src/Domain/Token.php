<?php declare(strict_types=1);

namespace Link0\Bunq\Domain;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

final class Token
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
    private $token;

    /**
     * @param array $token
     * @return Token
     */
    public static function fromArray(array $token)
    {
        $timezone = new DateTimeZone('UTC');
        $t = new Token();
        $t->id = Id::fromInteger(intval($token['id']));
        $t->created = new DateTimeImmutable($token['created'], $timezone);
        $t->updated = new DateTimeImmutable($token['updated'], $timezone);
        $t->token = $token['token'];
        return $t;
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
    public function token(): string
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->token();
    }
}
