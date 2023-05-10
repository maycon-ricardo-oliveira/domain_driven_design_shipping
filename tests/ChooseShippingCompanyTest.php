<?php

use Application\ShippingService;
use Domain\ShippingCompany;
use Domain\ShippingCompanyRepository;
use Domain\ZipcodeRange;
use Infrastructure\ShippingCompanyMemoryRepository;
use PHPUnit\Framework\TestCase;

class ShippingServiceTest extends TestCase
{
    private ShippingService $shippingService;
    private ShippingCompanyRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new ShippingCompanyMemoryRepository();
        $this->shippingService = new ShippingService($this->repository);

        // Popula o repositório com algumas transportadoras de exemplo
        $shippingCompany1 = new ShippingCompany('Transportadora A', 'Brasil', [
            new ZipcodeRange('10000', '20000', 3, 10.00),
            new ZipcodeRange('30000', '40000', 2, 12.50),
        ]);

        $shippingCompany2 = new ShippingCompany('Transportadora B', 'Brasil', [
            new ZipcodeRange('15000', '25000', 4, 8.50),
            new ZipcodeRange('35000', '45000', 3, 9.00),
        ]);

        $shippingCompany3 = new ShippingCompany('Transportadora C', 'Argentina', [
            new ZipcodeRange('15000', '25000', 4, 9.50),
            new ZipcodeRange('35000', '45000', 3, 10.00),
        ]);

        $shippingCompany4 = new ShippingCompany('Transportadora D', 'Argentina', [
            new ZipcodeRange('10000', '25000', 4, 8.50),
            new ZipcodeRange('20000', '35000', 3, 9.00),
        ]);

        $this->repository->add($shippingCompany1);
        $this->repository->add($shippingCompany2);
        $this->repository->add($shippingCompany3);
        $this->repository->add($shippingCompany4);
    }

    public function testFindCheapestShippingCompany(): void
    {
        // Teste para um CEP válido no Brasil
        $cepBrasil = '25000';
        $countryBrasil = 'Brasil';
        $cheapestShippingCompanyBrasil = $this->shippingService->findCheapestShippingCompany($cepBrasil, $countryBrasil);
        $this->assertInstanceOf(ShippingCompany::class, $cheapestShippingCompanyBrasil);
        $this->assertEquals('Transportadora B', $cheapestShippingCompanyBrasil->name);
    }

    public function testDifferentCountry()
    {
        // Teste para um CEP válido na Argentina
        $cepArgentina = '20001';
        $countryArgentina = 'Argentina';
        $cheapestShippingCompanyArgentina = $this->shippingService->findCheapestShippingCompany($cepArgentina, $countryArgentina);
        $this->assertInstanceOf(ShippingCompany::class, $cheapestShippingCompanyArgentina);
        $this->assertEquals('Transportadora D', $cheapestShippingCompanyArgentina->name);
    }

    public function testInvalidCEP()
    {
        $cepInvalido = '99999';
        $countryInvalido = 'Brasil';
        $cheapestShippingCompanyInvalido = $this->shippingService->findCheapestShippingCompany($cepInvalido, $countryInvalido);
        $this->assertNull($cheapestShippingCompanyInvalido);
    }
}
