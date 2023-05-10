<?php

namespace Domain;

class ZipcodeRange
{
    public string $minZipcode;
    public string $maxZipcode;
    public int $deliveryTime;
    public float $price;

    public function __construct(string $minZipcode, string $maxZipcode, int $deliveryTime, float $price)
    {
        $this->minZipcode = $minZipcode;
        $this->maxZipcode = $maxZipcode;
        $this->deliveryTime = $deliveryTime;
        $this->price = $price;
    }
}