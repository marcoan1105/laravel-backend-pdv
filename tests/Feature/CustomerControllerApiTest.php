<?php

namespace Tests\Feature;

use App\Http\Controllers\CustomerController;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerControllerApiTest extends TestCase
{
    private $user;
    private $tokensResponse;
    /**
     * A basic feature test example.
     *
     * @return void
     */
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


    public function testCreateCustomerWithAllParams(){
        $customer = new CustomerController();
        $customer->deleteByDocument("123456");

        $response = $this->post('/customer', [
            "name" => "Unit",
            "document" => "123456",
            "address" => "address",
            "number" => "123456",
            "obs" => "obs",
            "email" => "email@email.com",
            "phone" => "1111111111",
            "block" => 0,
            "inactive" => 0
        ], [
            "Authorization" => $this->tokensResponse->token_type . " " . $this->tokensResponse->access_token
        ]);

        $response->assertJson([
            "status" => true
        ]);
    }

    public function testeCreateCustomerWithDuplicateDocument(){
        $response = $this->post('/customer', [
            "name" => "Unit",
            "document" => "123456",
            "address" => "address",
            "number" => "123456",
            "obs" => "obs",
            "email" => "email@email.com",
            "phone" => "1111111111",
            "block" => 0,
            "inactive" => 0
        ], [
            "Authorization" => $this->tokensResponse->token_type . " " . $this->tokensResponse->access_token
        ]);

        $response->assertJsonStructure([
            "errors" => [["document"]]
        ]);
    }
}
