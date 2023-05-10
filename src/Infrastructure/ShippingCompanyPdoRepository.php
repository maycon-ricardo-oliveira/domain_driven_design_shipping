<?php

namespace Infrastructure;

use Adapters\MySqlAdapter\DBConnection;
use Domain\ShippingCompany;
use Domain\ShippingCompanyRepository;
use Domain\ZipcodeRange;

class ShippingCompanyPdoRepository implements ShippingCompanyRepository
{
    private DBConnection $connection;

    public function __construct(DBConnection $connection)
    {
        $this->connection = $connection;
        $this->createTables();
    }

    private function createTables()
    {
        $this->connection->query('CREATE TABLE IF NOT EXISTS shipping_companies (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            country TEXT NOT NULL
        )');

        $this->connection->query("CREATE TABLE IF NOT EXISTS shipping_company_zipcode_ranges (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            shipping_company_id INTEGER NOT NULL,
            min_zipcode TEXT NOT NULL,
            max_zipcode TEXT NOT NULL,
            delivery_time INTEGER NOT NULL,
            price FLOAT NOT NULL,
            FOREIGN KEY (shipping_company_id) REFERENCES shipping_companies (id)
        )");
    }

    public function getAllShippingCompanies(): array
    {
        $shippingCompaniesData = $this->connection->query('
        select * from shipping_companies sc
        inner join shipping_company_zipcode_ranges sczr ON sc.id = sczr.shipping_company_id
    ');

        $shippingCompanies = [];
        $currentCompanyId = null;

        foreach ($shippingCompaniesData as $data) {
            if ($data['id'] !== $currentCompanyId) {
                $currentCompanyId = $data['id'];
                $shippingCompanies[] = new ShippingCompany($data['name'], $data['country'], []);
            }

            $zipcodeRange = new ZipcodeRange(
                $data['min_zipcode'],
                $data['max_zipcode'],
                $data['delivery_time'],
                $data['price']
            );

            $lastShippingCompany = end($shippingCompanies);
            $lastShippingCompany->zipcodeRanges[] = $zipcodeRange;
        }

        return $shippingCompanies;
    }

    public function add(ShippingCompany $shippingCompany): void
    {
        $query = "insert into shipping_companies (name, country) values (?, ?)";
        $params = [$shippingCompany->name, $shippingCompany->country];
        $this->connection->query($query, $params);

        $shippingCompanyId = $this->connection->lastInsertId();

        foreach ($shippingCompany->zipcodeRanges as $zipcodeRange) {
            $query = "insert into shipping_company_zipcode_ranges (shipping_company_id, min_zipcode, max_zipcode, delivery_time, price) values (?, ?, ?, ?, ?)";
            $params = [$shippingCompanyId, $zipcodeRange->minZipcode, $zipcodeRange->maxZipcode, $zipcodeRange->deliveryTime, $zipcodeRange->price];
            $this->connection->query($query, $params);
        }
    }

}
