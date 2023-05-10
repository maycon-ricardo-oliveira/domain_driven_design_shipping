<?php

use Adapters\MySqlAdapter\SqLite;
use Application\ShippingService;
use Domain\ShippingCompany;
use Domain\ShippingCompanyRepository;
use Domain\ZipcodeRange;
use Infrastructure\ShippingCompanyMemoryRepository;
use Infrastructure\ShippingCompanyPdoRepository;
use PHPUnit\Framework\TestCase;

class ChooseShippingCompanyUsingDatabaseTest extends TestCase
{
    private ShippingService $shippingService;
    private ShippingCompanyRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $connection = new SqLite();
        $this->repository = new ShippingCompanyPdoRepository($connection);
        $this->shippingService = new ShippingService($this->repository);

        // Popula o repositório com algumas transportadoras de exemplo
        $shippingCompany1 = new ShippingCompany('Transportadora A', 'Brasil', [
            new ZipcodeRange('10000', '20000', 3, 10.00),
            new ZipcodeRange('30000', '40000', 2, 12.50),
        ]);

        $shippingCompany2 = new ShippingCompany('Transportadora B', 'Brasil', [
            new ZipcodeRange('15000', '25000', 4, 7.50),
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
}