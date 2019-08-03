<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductControllerApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
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

    public function testProductWithAllParams(){
        $response = $this->post('/product', [
            "description" => "Product Test",
            "unit" => "PC",
            "ncm" => "1231232",
            "c" => "00000000000",
            "value" => "100.00",
            "quantities" => "0"
        ], [
            "Authorization" => $this->tokensResponse->token_type . " " . $this->tokensResponse->access_token
        ]);

        $response->assertJson([
            "status" => true
        ]);
    }
}
