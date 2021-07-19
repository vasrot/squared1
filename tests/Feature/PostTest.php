<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_posts_return_json() {
        $user = new User;
        $user->name = 'test';
        $user->email = 'test@test.com';
        $user->password = Hash::make('123456');
        $user->save();

        $posts = Post::factory()->count(3)->make([
            'user_id' => $user->id
        ]);

        $response = $this->get('/api/post/');
        $response->assertStatus(200);
    }

}
