<?php declare(strict_types = 1);

namespace Link0\Bunq\Domain;

use Assert\Assertion;

final class Id
{
    /**
     * @var int
     */
    private $id;

    /**
     * @param int $id
     */
    private function __construct(int $id)
    {
        Assertion::greaterOrEqualThan($id, 0, 'Id must be greater or equal to 0');
        $this->id = $id;
    }

    /**
     * @param int $id
     * @return Id
     */
    public static function fromInteger(int $id): Id
    {
        return new Id($id);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->id;
    }
}
