<?php

namespace Infrastructure;

use Domain\ShippingCompany;
use Domain\ShippingCompanyRepository;

class ShippingCompanyMemoryRepository implements ShippingCompanyRepository
{
    private array $shippingCompanies;

    public function __construct()
    {
        $this->shippingCompanies = [];
    }

    public function getAllShippingCompanies(): array
    {
        return $this->shippingCompanies;
    }

    public function add(ShippingCompany $shippingCompany): void
    {
        $this->shippingCompanies[] = $shippingCompany;
    }
}