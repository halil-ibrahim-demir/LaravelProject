<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserRoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
    }

    public function test_user_can_have_roles_attached()
    {
        $user = User::factory()->create();
        $adminRole = Role::where('name', 'admin')->first();

        $user->roles()->attach($adminRole);

        $this->assertTrue($user->hasRole('admin'));
        $this->assertFalse($user->hasRole('user'));
        $this->assertTrue($user->hasAnyRole(['admin', 'user']));
    }

    public function test_guest_is_redirected_to_login_from_admin_dashboard()
    {
        $response = $this->get('/admin');

        $response->assertRedirect(route('login'));
    }

    public function test_standard_user_cannot_access_admin_dashboard()
    {
        $user = User::factory()->create();
        $userRole = Role::where('name', 'user')->first();
        $user->roles()->attach($userRole);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertStatus(403);
    }

    public function test_admin_user_can_access_admin_dashboard()
    {
        $admin = User::factory()->create();
        $adminRole = Role::where('name', 'admin')->first();
        $admin->roles()->attach($adminRole);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Admin Paneline Hoş Geldiniz!');
    }

    public function test_user_can_login_with_correct_credentials_and_redirects()
    {
        $password = 'secret-password';
        $user = User::factory()->create([
            'password' => Hash::make($password),
        ]);
        $userRole = Role::where('name', 'user')->first();
        $user->roles()->attach($userRole);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/');
    }

    public function test_admin_redirects_to_admin_dashboard_after_login()
    {
        $password = 'secret-password';
        $admin = User::factory()->create([
            'password' => Hash::make($password),
        ]);
        $adminRole = Role::where('name', 'admin')->first();
        $admin->roles()->attach($adminRole);

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => $password,
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect('/admin');
    }

    public function test_user_cannot_login_with_incorrect_credentials()
    {
        $user = User::factory()->create([
            'password' => Hash::make('correct-password'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect(route('login'));
    }
}
