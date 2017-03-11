<?php declare(strict_types=1);

namespace Link0\Bunq\Domain;

final class UserPerson extends User
{
    /**
     * @param array $userPerson
     * @return UserPerson
     */
    public static function fromArray(array $userPerson)
    {
        return new self($userPerson);
    }
}
