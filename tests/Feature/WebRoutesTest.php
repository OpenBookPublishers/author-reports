<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WebRoutesTest extends TestCase
{

    /**
     * Test GET '/'
     *
     * @return void
     */
    public function testGetRoot()
    {
        $response = $this->get('/');

        $response->assertRedirect('/dashboard');
    }

    /**
     * Test GET '/login'.
     *
     * @return void
     */
    public function testGetLogin()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /**
     * Test GET '/dashboard' as authenticated user
     *
     * @return void
     */
    public function testDashboardAuthenticated()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Test GET '/dashboard' as unauthenticated user
     *
     * @return void
     */
    public function testDashboardUnauthenticated()
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    /**
     * Test GET '/admin/books' as authenticated user
     *
     * @return void
     */
    public function testBookAdminAuthenticated()
    {
        $user = factory(User::class)->create();
        $user->admin = 1;

        $response = $this->actingAs($user)->get('/admin/books');

        $response->assertStatus(200);
    }

    /**
     * Test GET '/admin/books' as unauthenticated user
     *
     * @return void
     */
    public function testBookAdminUnauthenticated()
    {
        // not logged in
        $response = $this->get('/admin/books');
        $response->assertRedirect('/login');

        // logged in, but not an administator
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->get('/admin/books');
        // when a user is logged in but not an admin it will first be
        // redirected to login, then, since they're already logged in,
        // to the homepage; hence we are asserting /login, not /dashboard
        $response->assertRedirect('/login');
    }

    /**
     * Test GET '/admin/books' as authenticated user
     *
     * @return void
     */
    public function testUserAdminAuthenticated()
    {
        $user = factory(User::class)->create();
        $user->admin = 1;

        $response = $this->actingAs($user)->get('/admin/users');

        $response->assertStatus(200);
    }

    /**
     * Test GET '/admin/books' as unauthenticated user
     *
     * @return void
     */
    public function testUserAdminUnauthenticated()
    {
        // not logged in
        $response = $this->get('/admin/users');
        $response->assertRedirect('/login');

        // logged in, but not an administator
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->get('/admin/users');
        $response->assertRedirect('/login');
    }
}
