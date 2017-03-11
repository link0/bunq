<?php declare(strict_types=1);

namespace Link0\Bunq\Domain;

final class Address
{
    /**
     * @var string
     */
    private $street;

    /**
     * @var string
     */
    private $houseNumber;

    /**
     * @var string
     */
    private $postalCode;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $province;

    /**
     * @param string $street
     * @param string $houseNumber
     * @param string $postalCode
     * @param string $city
     * @param string $country
     * @param string $province
     */
    public function __construct(
        string $street,
        string $houseNumber,
        string $postalCode,
        string $city,
        string $country,
        string $province
    ) {
        $this->street = $street;
        $this->houseNumber = $houseNumber;
        $this->postalCode = $postalCode;
        $this->city = $city;
        $this->country = $country;
        $this->province = $province;
    }

    /**
     * @param array $address
     * @return Address
     */
    public static function fromArray(array $address): Address
    {
        return new Address(
            $address['street'],
            $address['house_number'],
            $address['postal_code'],
            $address['city'],
            $address['country'],
            $address['province'] === null ? '' : $address['province']
        );
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'street' => $this->street(),
            'house_number' => $this->houseNumber(),
            'postal_code' => $this->postalCode(),
            'city' => $this->city(),
            'country' => $this->country(),
            'province' => $this->province(),
        ];
    }

    /**
     * @return string
     */
    public function street(): string
    {
        return $this->street;
    }

    /**
     * @return string
     */
    public function houseNumber(): string
    {
        return $this->houseNumber;
    }

    /**
     * @return string
     */
    public function postalCode(): string
    {
        return $this->postalCode;
    }

    /**
     * @return string
     */
    public function city(): string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function country(): string
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function province(): string
    {
        return $this->province;
    }
}
