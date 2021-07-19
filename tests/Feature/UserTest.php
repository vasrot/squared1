<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_can_not_see_own_posts() {
        $user = new User;
        $user->name = 'test';
        $user->email = 'test@test.com';
        $user->password = Hash::make('123456');
        $user->save();

        Post::create([
            'title' => 'asdfasdf',
            'description' => 'asdfaweefw',
            'user_id' => $user->id
        ]);

        $response = $this->get('/api/user/' . $user->id . '/posts');
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_see_own_posts() {
        $user = new User;
        $user->name = 'test';
        $user->email = 'test@test.com';
        $user->password = Hash::make('123456');
        $user->save();

        $response = $this->postJson('/api/login', ['email' => 'test@test.com', 'password' => '123456']);

        Post::create([
            'title' => 'asdfasdf',
            'description' => 'asdfaweefw',
            'user_id' => $user->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $response['token'],
        ])->get('/api/user/' . $user->id . '/posts');
        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_not_see_other_user_posts() {
        $user = new User;
        $user->name = 'test';
        $user->email = 'test@test.com';
        $user->password = Hash::make('123456');
        $user->save();
        $first_id = $user->id;

        $user = new User;
        $user->name = 'test2';
        $user->email = 'test2@test.com';
        $user->password = Hash::make('123456');
        $user->save();

        $response = $this->postJson('/api/login', ['email' => 'test2@test.com', 'password' => '123456']);

        Post::create([
            'title' => 'asdfasdf',
            'description' => 'asdfaweefw',
            'user_id' => $first_id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $response['token'],
        ])->get('/api/user/' . $first_id . '/posts');
        $response->assertStatus(401);
    }
}
