<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_guest_is_redirected_to_login_from_home()
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_master_items()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test-' . uniqid() . '@example.com',
        ]);

        $response = $this->actingAs($user)->get('/master-items');

        $response->assertOk();
    }
}
