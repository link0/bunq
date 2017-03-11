<?php declare(strict_types=1);

namespace Link0\Bunq\Domain;

final class UserCompany extends User
{
    /**
     * @param array $userCompany
     * @return UserCompany
     */
    public static function fromArray(array $userCompany)
    {
        return new self($userCompany);
    }
}
