<?php

namespace Tests\Feature;

use App\Http\Controllers\UserController;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerApiTest extends TestCase
{
    private $email;
    private $secret;
    private $pass;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    protected function setUp()
    {
        parent::setUp();
        $this->email = "unittest@test.com";
        $this->secret = "DHhfAPStCiaYu0h4O4UDvZwkDuDpAb8W7vHteJx4";
        $this->pass = "123456";
    }

    protected function tearDown(){
        $controllerUser = new UserController();
        $controllerUser->deleteUserByEmail($this->email);
    }

    public function testCreateUserWithAllParams()
    {
        $response = $this->callPutUserUrl([
            "name" => "Unit Test",
            "email" => $this->email,
            "password" => "123456"
        ]);

        $response->assertJson([
            "status" => true
        ]);
    }

    public function testCreateUserWithoutPassword()
    {
        $response = $this->callPutUserUrl([
            "name" => "Unit Test",
            "email" => $this->email
        ]);

        $response->assertJsonStructure([
            "status",
            "msg",
            "errors" => [
                "password"
            ]
        ]);
    }

    public function testCreateUserWithoutParams(){
        $response = $this->callPutUserUrl([]);
        $response->assertJsonStructure([
            "status",
            "msg",
            "errors" => [
                "name", "email", "password"
            ]
        ]);
    }

    public function testAuthWithValidUser(){
        $response = $this->callPutUserUrl([
            "name" => "Unit Test",
            "email" => $this->email,
            "password" => "123456"
        ]);

        $response = $this->post('/oauth/token', [
            "grant_type" => "password",
            "client_id" => 2,
            "client_secret" => $this->secret,
            "username" =>  $this->email,
            "password" => $this->pass
        ]);

        $response->assertJsonStructure([
            "token_type", "expires_in", "access_token", "refresh_token"
        ]);
    }

    public function testAuthWithNotValidUser(){
        $response = $this->post('/oauth/token', [
            "grant_type" => "password",
            "client_id" => 2,
            "client_secret" => $this->secret,
            "username" =>  $this->email,
            "password" => $this->pass
        ]);

        $response->assertJson([
            "error" => "invalid_credentials"
        ]);
    }

    protected function callPutUserUrl($data){
        return $this->put('/user', $data);
    }

}
