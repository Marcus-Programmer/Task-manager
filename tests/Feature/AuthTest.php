<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test login page displays correctly
     */
    public function test_login_page_displays(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /**
     * Test register page displays correctly
     */
    public function test_register_page_displays(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    /**
     * Test user can login with correct credentials
     */
    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/tasks');
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test user cannot login with incorrect credentials
     */
    public function test_user_cannot_login_with_incorrect_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /**
     * Test user can register successfully
     */
    public function test_user_can_register_successfully(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/tasks');

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'newuser@example.com',
        ]);

        $this->assertAuthenticated();
    }

    /**
     * Test guest user redirected to login when accessing protected routes
     */
    public function test_guest_redirected_to_login_on_protected_routes(): void
    {
        $response = $this->get('/tasks');

        $response->assertRedirect('/login');
    }

    /**
     * Test root route redirects properly based on auth status
     */
    public function test_root_route_redirects_based_on_auth_status(): void
    {
        // Guest user should be redirected to login
        $response = $this->get('/');
        $response->assertRedirect('/login');

        // Authenticated user should be redirected to tasks
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/');
        $response->assertRedirect('/tasks');
    }
}
