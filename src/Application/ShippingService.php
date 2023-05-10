<?php

namespace Application;

use Domain\ShippingCompany;
use Domain\ShippingCompanyRepository;
use Domain\ZipcodeRange;

class ShippingService
{
    private ShippingCompanyRepository $shippingCompanyRepository;

    public function __construct(ShippingCompanyRepository $shippingCompanyRepository)
    {
        $this->shippingCompanyRepository = $shippingCompanyRepository;
    }

    public function findCheapestShippingCompany(string $zipcode, string $country): ?ShippingCompany
    {
        $cheapestShippingCompany = null;
        $cheapestPrice = PHP_FLOAT_MAX;
        $shippingCompanies = $this->shippingCompanyRepository->getAllShippingCompanies();
        foreach ($shippingCompanies as $shippingCompany) {
            if ($shippingCompany->country !== $country) {
                continue; // Verifica se a transportadora atende ao país desejado
            }

            foreach ($shippingCompany->zipcodeRanges as $zipcodeRange) {
                if ($this->isZipcodeInRange($zipcode, $zipcodeRange)) {
                    if ($zipcodeRange->price < $cheapestPrice) {
                        $cheapestShippingCompany = $shippingCompany;
                        $cheapestPrice = $zipcodeRange->price;
                    }
                    break; // Interrompe o loop interno, já que encontrou uma faixa de CEP válida
                }
            }
        }

        return $cheapestShippingCompany;
    }

    private function isZipcodeInRange(string $zipcode, ZipcodeRange $zipcodeRange): bool
    {
        return $zipcode >= $zipcodeRange->minZipcode && $zipcode <= $zipcodeRange->maxZipcode;
    }

    private function registerDefaultShippingCompany(): ShippingCompany
    {
        $country = 'Brasil';
        $name = 'Transportadora Padrão';
        return new ShippingCompany($name,$country, []);
    }
}
