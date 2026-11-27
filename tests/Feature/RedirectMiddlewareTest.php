<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\Redirect;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RedirectMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_get_requests_follow_an_active_redirect(): void
    {
        Redirect::create([
            'from_url' => '/old-page',
            'to_url' => '/products',
            'status_code' => 301,
            'is_active' => true,
        ]);

        $this->get('/old-page')->assertRedirect('/products');
    }

    public function test_contact_form_post_is_not_swallowed_by_a_redirect(): void
    {
        Redirect::create([
            'from_url' => '/contact',
            'to_url' => '/products',
            'status_code' => 301,
            'is_active' => true,
        ]);

        $this->from('/contact')->post('/contact', [
            'name' => 'Asha Rao',
            'email' => 'asha@example.com',
            'phone' => '9876543210',
            'message' => 'Need a quote for a vinyl cutter.',
        ])->assertRedirect('/contact');

        $this->assertDatabaseHas('leads', [
            'email' => 'asha@example.com',
            'type' => 'contact',
        ]);
        $this->assertSame(1, Lead::count());
    }

    public function test_redirect_to_the_same_url_does_not_loop(): void
    {
        Redirect::create([
            'from_url' => '/contact',
            'to_url' => '/contact',
            'status_code' => 301,
            'is_active' => true,
        ]);

        $this->get('/contact')->assertOk();
    }

    public function test_login_and_admin_paths_are_not_redirected(): void
    {
        Redirect::create([
            'from_url' => '/login',
            'to_url' => '/products',
            'status_code' => 301,
            'is_active' => true,
        ]);

        Redirect::create([
            'from_url' => '/admin',
            'to_url' => '/products',
            'status_code' => 301,
            'is_active' => true,
        ]);

        $this->get('/login')->assertOk();
        $this->get('/admin')->assertRedirect('/login');
    }
}
