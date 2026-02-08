<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;  // ← IMPORT INI

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function staff_can_login()
    {
        $staff = User::factory()->create([
            'email' => 'staff@test.com',
            'password' => bcrypt('password'),
            'role' => 'staff'
        ]);

        $response = $this->post('/login', [
            'email' => 'staff@test.com',
            'password' => 'password'
        ]);

        $response->assertRedirect('/staff/dashboard');
        $this->assertAuthenticated();
    }

    /** @test */
    public function guest_cannot_access_protected_routes()
    {
        $response = $this->get('/staff/dashboard');
        $response->assertRedirect('/login');
    }
}
