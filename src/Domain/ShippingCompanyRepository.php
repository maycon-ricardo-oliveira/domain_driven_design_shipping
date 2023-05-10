<?php

namespace Domain;

interface ShippingCompanyRepository
{
    /**
     * Obtém todas as empresas de transporte disponíveis.
     *
     * @return array<ShippingCompany>
     */
    public function getAllShippingCompanies(): array;

    public  function add(ShippingCompany $shippingCompany): void;
}
