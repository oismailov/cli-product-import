<?php

namespace App\Tests\Service\Import;

use App\Tests\Service\BaseService;
use App\Validator;

/**
 * Class ImportTest
 * @package App\Tests\Service\Import
 */
class ImportTest extends BaseService
{
    /**
     * Test successful input data.
     *
     * @param int $rowId
     * @param array $data
     *
     * @return void
     *
     * @dataProvider getValidInputData
     */
    public function testSuccessInputData(int $rowId, array $data): void
    {
        $validator = $this->getValidator();
        $productRow = new Validator\Product($rowId, $data);
        $errors = $validator->validate($productRow);
        $this->assertEquals(0, $errors->count());
    }

    /**
     * Test invalid input data.
     *
     * @param int $rowId
     * @param array $data
     *
     * @return void
     *
     * @dataProvider getInvalidInputData
     */
    public function testInvalidInputData(int $rowId, array $data): void
    {
        $validator = $this->getValidator();
        $productRow = new Validator\Product($rowId, $data);
        $errors = $validator->validate($productRow);
        $this->assertEquals(1, $errors->count());

    }

    /**
     * Data provider with valid input.
     *
     * @return array
     */
    public function getValidInputData(): array
    {
        return [
            [
                'row_id' => 1,
                'data' => [
                    'SKU' => "111111",
                    'description' => "this is product 111111 description",
                    'normalPrice' => "10.50",
                    'specialPrice' => "8.50",
                ]
            ], [
                'row_id' => 2,
                'data' => [
                    'SKU' => "222222",
                    'description' => "this is product 222222 description",
                    'normalPrice' => "10.50",
                    'specialPrice' => null,
                ]
            ], [
                'row_id' => 3,
                'data' => [
                    'SKU' => "333333",
                    'description' => "this is product 333333 description",
                    'normalPrice' => "10.50",
                    'specialPrice' => "",
                ]
            ]
        ];
    }

    /**
     * Data provider with invalid input.
     *
     * @return array
     */
    public function getInvalidInputData(): array
    {
        return [
            [
                'row_id' => 1,
                'data' =>
                    [
                        'SKU' => "111111",
                        'description' => "this is product with non float normalPrice",
                        'normalPrice' => "10",
                        'specialPrice' => "8.50",
                    ]
            ], [
                'row_id' => 2,
                'data' =>
                    [
                        'SKU' => "222222",
                        'description' => "this is product with specialPrice higher than normalPrice",
                        'normalPrice' => "10.50",
                        'specialPrice' => "11.00",
                    ]
            ], [
                'row_id' => 3,
                'data' =>
                    [
                        'SKU' => "333333",
                        'description' => "this is product with non float specialPrice",
                        'normalPrice' => "10.50",
                        'specialPrice' => "5",
                    ]
            ], [
                'row_id' => 4,
                'data' => [
                    'SKU' => "444",
                    'description' => "this is product with sku that doesn't match min value of symbols (5)",
                    'normalPrice' => "10.50",
                    'specialPrice' => "5.00",
                ]
            ], [
                'row_id' => 5,
                'data' => [
                    'SKU' => "555555",
                    'description' => "this is product with null value for normalPrice",
                    'normalPrice' => null,
                    'specialPrice' => null,
                ]
            ], [
                'row_id' => 6,
                'data' => [
                    //null value for description
                    'SKU' => "666666",
                    'description' => null,
                    'normalPrice' => "40.5",
                    'specialPrice' => null,
                ]
            ], [
                'row_id' => 7,
                'data' => [
                    'SKU' => null,
                    'description' => "this is product with null value for sku",
                    'normalPrice' => "40.5",
                    'specialPrice' => null,
                ]
            ], [
                'row_id' => 8,
                'data' => [
                    'SKU' => "888888",
                    'description' => "<script>alert('hello from XSS!!!');</script>",
                    'normalPrice' => "40.5",
                    'specialPrice' => "20.00",
                ],
            ]
        ];
    }
}