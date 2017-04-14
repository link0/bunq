<?php declare(strict_types = 1);

namespace Link0\Bunq\Domain;

final class LabelMonetaryAccount
{
    /**
     * @var string
     */
    private $iban;

    /**
     * @var string
     */
    private $displayName;

    /**
     * LabelMonetaryAccount constructor.
     * @param array $labelMonetaryAccount
     */
    private function __construct(array $labelMonetaryAccount)
    {
        $this->iban = $labelMonetaryAccount['iban'];
        $this->displayName = $labelMonetaryAccount['display_name'];
    }

    /**
     * @param array $labelMonetaryAccount
     * @return LabelMonetaryAccount
     */
    public static function fromArray(array $labelMonetaryAccount): LabelMonetaryAccount
    {
        return new self($labelMonetaryAccount);
    }

    /**
     * @return string
     */
    public function iban(): string
    {
        return $this->iban;
    }

    /**
     * @return string
     */
    public function displayName(): string
    {
        return $this->displayName;
    }
}
