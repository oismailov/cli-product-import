<?php

namespace App\Tests\Service\Import;

use App\Tests\Service\BaseService;
use App\Validator;

class ImportTest extends BaseService
{
    public function testSuccessInputData()
    {
        $validator = $this->getValidator();
        $validRecords = $this->getValidDataInput();
        foreach ($validRecords as $key => $data) {
            $productRow = new Validator\Product($key, $data);
            $errors = $validator->validate($productRow);
            $this->assertEquals(0, $errors->count());
        }
    }

    public function testInvalidInputData()
    {
        $validator = $this->getValidator();
        $invalidRecords = $this->getInvalidInputData();
        foreach ($invalidRecords as $key => $data) {
            $productRow = new Validator\Product($key, $data);
            $errors = $validator->validate($productRow);
            $this->assertEquals(1, $errors->count());
        }
    }

    private function getValidDataInput()
    {
        return [
            [
                'SKU' => "111111",
                'description' => "this is product 111111 description",
                'normalPrice' => "10.50",
                'specialPrice' => "8.50",
            ],
            [
                'SKU' => "222222",
                'description' => "this is product 222222 description",
                'normalPrice' => "10.50",
                'specialPrice' => null,
            ],
            [
                'SKU' => "333333",
                'description' => "this is product 333333 description",
                'normalPrice' => "10.50",
                'specialPrice' => "",
            ]
        ];
    }

    private function getInvalidInputData()
    {
        return [
            [
                'SKU' => "111111",
                'description' => "this is product with non float normalPrice",
                'normalPrice' => "10",
                'specialPrice' => "8.50",
            ],
            [
                'SKU' => "222222",
                'description' => "this is product with specialPrice higher than normalPrice",
                'normalPrice' => "10.50",
                'specialPrice' => "11.00",
            ],
            [
                'SKU' => "333333",
                'description' => "this is product with non float specialPrice",
                'normalPrice' => "10.50",
                'specialPrice' => "5",
            ],
            [
                'SKU' => "444",
                'description' => "this is product with sku that doesn't match min value of symbols (5)",
                'normalPrice' => "10.50",
                'specialPrice' => "5.00",
            ],
            [
                'SKU' => "555555",
                'description' => "this is product with null value for normalPrice",
                'normalPrice' => null,
                'specialPrice' => null,
            ],
            [
                //null value for description
                'SKU' => "666666",
                'description' => null,
                'normalPrice' => "40.5",
                'specialPrice' => null,
            ],
            [
                'SKU' => null,
                'description' => "this is product with null value for sku",
                'normalPrice' => "40.5",
                'specialPrice' => null,
            ]
        ];
    }
}