<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{

    use RefreshDatabase;

    public function test_not_found_user_login_returns_null() {
        $user = User::where('email', '123412341234@asd.com')->first();
        $this->assertNull($user);
    }
}
