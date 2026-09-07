<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Config;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        Config::set('app.url', 'http://localhost');
        $response = $this->get('http://localhost/');

        $response->assertStatus(200);
    }

    public function test_birthday_content_is_protected(): void
    {
        $this->get('http://localhost/surprise')->assertRedirect();
        $this->get('http://localhost/admin/dashboard')->assertRedirect();
    }
}
