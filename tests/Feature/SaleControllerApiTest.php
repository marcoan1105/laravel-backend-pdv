<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SaleControllerApiTest extends TestCase
{
    private $user;
    private $tokensResponse;

    public function setUp(){
        parent::setUp();
        $this->user = [
            "grant_type" => "password",
            "client_id" => 2,
            "client_secret" => "DHhfAPStCiaYu0h4O4UDvZwkDuDpAb8W7vHteJx4",
            "username" =>  "john@email.com.br",
            "password" => "123"
        ];

        $this->tokensResponse = json_decode($this->post('/oauth/token', $this->user)->getContent());
    }

    public function testSaleWithAllParams(){
        $responseCustomer = $this->post('/customer', [
            "name" => "Unit",
            "document" => rand(100000, 999999),
            "address" => "address",
            "number" => "123456",
            "obs" => "obs",
            "email" => "email@email.com",
            "phone" => "1111111111",
            "block" => 0,
            "inactive" => 0
        ], [
            "Authorization" => $this->tokensResponse->token_type . " " . $this->tokensResponse->access_token
        ])->getContent();

        $responseCustomer = json_decode($responseCustomer);

        $response = $this->post('/sale', [
            "client_id" => $responseCustomer->data->id,
            "total" => "10.00",
            "discount" => "0.00",
            "date" => "2019-01-01",
            "final_value" => "10.00",
            "products"  => [
                [
                    "product_id" => "1",
                    "value" => "10.00",
                    "quant" => 1
                ]
            ],
            "payments" => [
                [
                    "payment_id" => "1",
                    "value" => "10.00"
                ]
            ]
        ], [
            "Authorization" => $this->tokensResponse->token_type . " " . $this->tokensResponse->access_token
        ]);

        $response->assertJson([
            "status" => true
        ]);
    }
}
