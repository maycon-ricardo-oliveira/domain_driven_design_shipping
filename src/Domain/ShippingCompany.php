<?php

namespace Domain;

class ShippingCompany
{
    public string $name;
    public string $country;
    public array $zipcodeRanges;

    public function __construct(string $name, string $country, array $zipcodeRanges)
    {
        $this->name = $name;
        $this->country = $country;
        $this->zipcodeRanges = $zipcodeRanges;
    }
}