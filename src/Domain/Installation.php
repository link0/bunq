<?php declare(strict_types = 1);

namespace Link0\Bunq\Domain;

final class Installation
{
    /**
     * @var Id
     */
    private $id;

    /**
     * @var string
     */
    private $token;

    /**
     * @param Id $id
     * @param string $token
     */
    public function __construct(Id $id, string $token)
    {
        $this->id = $id;
        $this->token = $token;
    }

    /**
     * @return Id
     */
    public function id(): Id
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function token(): string
    {
        return $this->token;
    }
}
