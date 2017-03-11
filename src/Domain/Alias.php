<?php declare(strict_types=1);

namespace Link0\Bunq\Domain;

use InvalidArgumentException;

final class Alias
{
    const TYPE_PHONE_NUMBER = 'PHONE_NUMBER';
    const TYPE_EMAIL = 'EMAIL';
    const TYPE_IBAN = 'IBAN';

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $value;

    /**
     * @var string
     */
    private $name;

    /**
     * @param string $type
     * @param string $value
     * @param string $name
     */
    public function __construct(string $type, string $value, string $name)
    {
        $this->guardValidType($type);

        $this->type = $type;
        $this->value = $value;
        $this->name = $name;
    }

    public static function fromArray(array $alias)
    {
        return new Alias(
            $alias['type'],
            $alias['value'],
            $alias['name']
        );
    }

    public function toArray()
    {
        return [
            'type' => $this->type(),
            'value' => $this->value(),
            'name' => $this->name(),
        ];
    }

    /**
     * @param string $type
     * @return void
     * @throws InvalidArgumentException
     */
    private function guardValidType(string $type)
    {
        switch ($type) {
            case self::TYPE_PHONE_NUMBER:
            case self::TYPE_EMAIL:
            case self::TYPE_IBAN:
                break;
            default:
                throw new InvalidArgumentException("Invalid Alias type: " . $type);
        }
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }
}
