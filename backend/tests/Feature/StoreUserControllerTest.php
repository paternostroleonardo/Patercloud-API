<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreUserControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_that_store_controller_create_user_as_expected(): void
    {
        $response = $this->post('/api/register', [
            'email' => 'new@cloud.com',
            'name' =>'new',
            'password' => 'password'
        ]);

        $response->assertStatus(201);
    }

    public function test_that_auth_controller_login_user_as_expected(): void
    {
        $response = $this->post('/api/login', [
            'email' => 'paternostro.leonardo@cloud.com',
            'password' => 'password'
        ]);

        $response->assertStatus(200);
    }
}
