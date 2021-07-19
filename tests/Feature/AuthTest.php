<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{

    use RefreshDatabase;

    public function test_login_user_not_found_returns_401() {
        $response = $this->postJson('/api/login', ['email' => '123123123@gasdgf.com', 'password' => '12312312']);
        $response->assertStatus(401);
    }

    public function test_login_email_not_send_returns_error_422() {
        $response = $this->postJson('/api/login', ['password' => '12312234']);
        $response->assertStatus(422);
    }

    public function test_login_password_not_send_returns_error_422() {
        $response = $this->postJson('/api/login', ['email' => '123123123@gasdgf.com']);
        $response->assertStatus(422);
    }

    public function test_login_wrong_password_returns_401() {
        User::create([
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => Hash::make('123456')
        ]);
        $response = $this->postJson('/api/login', ['email' => 'test@test.com', 'password' => '222222222']);
        $response->assertStatus(401);
    }

    public function test_login_success_returns_201() {
        User::create([
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => Hash::make('123456')
        ]);
        $response = $this->postJson('/api/login', ['email' => 'test@test.com', 'password' => '123456']);
        $response->assertStatus(201);
    }

    public function test_login_success_returns_json_with_user_and_token() {
        User::create([
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => Hash::make('123456')
        ]);
        $response = $this->postJson('/api/login', ['email' => 'test@test.com', 'password' => '123456']);
        $response->assertJsonStructure([
            'user' => [
                'id',
                'name',
                'email',
                'email_verified_at',
                'created_at',
                'updated_at'
            ],
            'token'
        ]);
    }
}
