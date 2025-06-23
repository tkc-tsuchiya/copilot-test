<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * Test login page is accessible
     */
    public function test_login_page_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('ログイン');
        $response->assertSee('ユーザー名');
        $response->assertSee('パスワード');
    }

    /**
     * Test login with valid credentials
     */
    public function test_login_with_valid_credentials(): void
    {
        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/dashboard');
    }

    /**
     * Test login with invalid credentials
     */
    public function test_login_with_invalid_credentials(): void
    {
        $response = $this->post('/login', [
            'username' => 'invalid',
            'password' => 'invalid',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['username']);
    }

    /**
     * Test dashboard requires authentication
     */
    public function test_dashboard_requires_authentication(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    /**
     * Test dashboard accessible after login
     */
    public function test_dashboard_accessible_after_login(): void
    {
        // Login first
        $this->post('/login', [
            'username' => 'admin',
            'password' => 'admin123',
        ]);

        // Now access dashboard
        $response = $this->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('ダッシュボード');
        $response->assertSee('admin');
    }

    /**
     * Test password encryption API
     */
    public function test_password_encryption_api(): void
    {
        $response = $this->postJson('/api/encrypt-password', [
            'password' => 'test123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'plaintext',
            'encrypted',
        ]);
        $response->assertJson([
            'plaintext' => 'test123',
        ]);

        // Verify the encrypted password is different from plaintext
        $data = $response->json();
        $this->assertNotEquals($data['plaintext'], $data['encrypted']);
        $this->assertTrue(password_verify('test123', $data['encrypted']));
    }

    /**
     * Test logout functionality
     */
    public function test_logout_functionality(): void
    {
        // Login first
        $this->post('/login', [
            'username' => 'admin',
            'password' => 'admin123',
        ]);

        // Logout
        $response = $this->post('/logout');
        $response->assertRedirect('/');

        // Verify we can't access protected routes
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }
}