<?php

namespace ValueObjects;

class Zipcode
{
    private string $zipcode;

    public function __construct(string $zipcode)
    {
        $this->zipcode = $zipcode;
    }

    public function getValue(): string
    {
        return $this->zipcode;
    }
}