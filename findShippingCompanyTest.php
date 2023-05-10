<?php

function findCheapestShippingCompany($zipcode, $country)
{
    $shippingCompanies = [
        [
            'name' => 'Transportadora A',
            'country' => 'Brazil',
            'zipcodeRanges' => [
                [
                    'minZipcode' => '10000',
                    'maxZipcode' => '20000',
                    'deliveryTime' => 3,
                    'price' => 10.00,
                ],
                [
                    'minZipcode' => '30000',
                    'maxZipcode' => '40000',
                    'deliveryTime' => 2,
                    'price' => 12.50,
                ],
            ],
        ],
        [
            'name' => 'Transportadora B',
            'country' => 'Brazil',
            'zipcodeRanges' => [
                [
                    'minZipcode' => '15000',
                    'maxZipcode' => '25000',
                    'deliveryTime' => 4,
                    'price' => 8.50,
                ],
                [
                    'minZipcode' => '35000',
                    'maxZipcode' => '45000',
                    'deliveryTime' => 3,
                    'price' => 9.00,
                ],
            ],
        ],
    ];

    $cheapestShippingCompany = null;
    $cheapestPrice = PHP_FLOAT_MAX;

    foreach ($shippingCompanies as $shippingCompany) {
        if ($shippingCompany['country'] !== $country) {
            continue;
        }

        foreach ($shippingCompany['zipcodeRanges'] as $zipcodeRange) {
            if ($zipcode >= $zipcodeRange['minZipcode'] && $zipcode <= $zipcodeRange['maxZipcode']) {
                if ($zipcodeRange['price'] < $cheapestPrice) {
                    $cheapestShippingCompany = $shippingCompany;
                    $cheapestPrice = $zipcodeRange['price'];
                }
                break;
            }
        }
    }

    return $cheapestShippingCompany;
}

// Exemplo de uso do endpoint
$zipcode = '18000';
$country = 'Brazil';

$cheapestShippingCompany = findCheapestShippingCompany($zipcode, $country);

if ($cheapestShippingCompany) {
    echo 'Transportadora mais barata: ' . $cheapestShippingCompany['name'] . PHP_EOL;
} else {
    echo 'Nenhuma transportadora encontrada para o CEP e país informados.'  . PHP_EOL;
}