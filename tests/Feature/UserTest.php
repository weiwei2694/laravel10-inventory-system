<?php

namespace Tests\Feature\Dashboard;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([RoleSeeder::class, UserSeeder::class]);
    }

    public function testIndexSuccess()
    {
        $admin = User::where('role', 2)->first();
        $role = $admin->role === Role::ADMIN ? 'Admin' : 'User';

        $this->actingAs($admin)
            ->get('http://127.0.0.1:8000/dashboard/users')
            ->assertStatus(200)
            ->assertSeeText(['All Users', 'Create New User'])
            ->assertSeeText(['#', 'Id', 'Name', 'Email', 'Role', 'Action']) # table header
            ->assertSeeText(['1', $admin->id, $admin->name, $admin->email, $role])
            ->assertSeeText(['N/A', 'View', 'Delete', 'Edit']); # field action
    }

    public function testIndexAuthorization()
    {
        $user = User::where('role', 1)->first();

        $this->actingAs($user)
            ->get('http://127.0.0.1:8000/dashboard/users')
            ->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }

    public function testCreateSuccess()
    {
        $admin = User::where('role', 2)->first();

        $this->actingAs($admin)
            ->get('/dashboard/users/create')
            ->assertStatus(200)
            ->assertSeeText(['Create New User', 'Back'])
            ->assertSeeText(['Name', 'Email', 'Password', 'Confirm Password']) # input field
            ->assertSeeText('Save'); # button submit
    }

    public function testCreateAuthorization()
    {
        $user = User::where('role', 1)->first();

        $this->get('/dashboard/users/create')
            ->assertStatus(302)
            ->assertRedirect('/login');

        $this->actingAs($user)
            ->get('/dashboard/users/create')
            ->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }

    public function testStoreSuccess()
    {
        $admin = User::where('role', 2)->first();

        $this->actingAs($admin)
            ->withSession(['_token' => csrf_token()])
            ->post('/dashboard/users', [
                'name' => 'test3',
                'email' => 'test3@gmail.com',
                'password' => 'password',
                'password_confirmation' => 'password'
            ])->assertStatus(302)
            ->assertRedirect('/dashboard/users')
            ->assertSessionHas('success', 'User successfully created.');
    }

    public function testStoreValidationFailed()
    {
        $admin = User::where('role', 2)->first();

        $this->actingAs($admin)
            ->withSession(['_token' => csrf_token()])
            ->post('/dashboard/users', [
                'name' => '',
                'email' => 'invalid email',
                'password' => 'password',
                'password_confirmation' => 'wrong'
            ])->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function testStoreAuthorization()
    {
        $user = User::where('role', 1)->first();

        $this->withSession(['_token' => csrf_token()])
            ->post('/dashboard/users', [
                'name' => 'test3',
                'email' => 'test3@gmail.com',
                'password' => 'password',
                'password_confirmation' => 'password'
            ])->assertStatus(302)
            ->assertRedirect('/login');

        $this->actingAs($user)
            ->withSession(['_token' => csrf_token()])
            ->post('/dashboard/users', [
                'name' => 'test3',
                'email' => 'test3@gmail.com',
                'password' => 'password',
                'password_confirmation' => 'password'
            ])->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }

    public function testEditSuccess()
    {
        $user = User::where('role', 1)->first();
        $admin = User::where('role', 2)->first();

        $this->actingAs($admin)
            ->get("/dashboard/users/$user->id/edit")
            ->assertStatus(200)
            ->assertSeeText(['Update User', 'Back'])
            ->assertSeeText(['Name', 'Email', 'New Password', 'Confirm New Password']) # input field
            ->assertSeeText('Save'); # button submit
    }

    public function testEditNotFound()
    {
        $admin = User::where('role', 2)->first();

        $this->actingAs($admin)
            ->get('/dashboard/users/' . $admin->id + 10 . '/edit')
            ->assertStatus(404)
            ->assertSeeText(['404', 'Not Found']);
    }

    public function testEditAuthenticatedUserCannotUpdateOwnProfile()
    {
        $admin = User::where('role', 2)->first();

        $this->actingAs($admin)
            ->get("/dashboard/users/$admin->id/edit")
            ->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }

    public function testEditCannotEditAdminUser()
    {
        $admin = User::where('role', 2)->first();
        $second_admin = User::factory()->create(['role' => 2]);

        $this->actingAs($admin)
            ->get("/dashboard/users/$second_admin->id/edit")
            ->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }

    public function testEditAuthorization()
    {
        $user = User::where('role', 1)->first();
        $admin = User::where('role', 2)->first();

        $this->get("/dashboard/users/$admin->id/edit")
            ->assertStatus(302)
            ->assertRedirect('/login');

        $this->actingAs($user)
            ->get("/dashboard/users/$admin->id/edit")
            ->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }

    public function testUpdateSuccess()
    {
        $user = User::where('role', 1)->first();
        $admin = User::where('role', 2)->first();

        $this->actingAs($admin)
            ->withSession(['_token' => csrf_token()])
            ->put("/dashboard/users/$user->id", [
                'name' => 'test3 update',
                'email' => 'test3update@gmail.com',
            ])->assertStatus(302)
            ->assertRedirect('/dashboard/users')
            ->assertSessionHas('success', 'User successfully updated.');
    }

    public function testUpdateValidationFailed()
    {
        $user = User::where('role', 1)->first();
        $admin = User::where('role', 2)->first();

        $this->actingAs($admin)
            ->withSession(['_token' => csrf_token()])
            ->put("/dashboard/users/$user->id", [
                'name' => '',
                'email' => 'invalid email',
            ])->assertSessionHasErrors(['name', 'email']);
    }

    public function testUpdateNotFound()
    {
        $user = User::where('role', 1)->first();
        $admin = User::where('role', 2)->first();

        $this->actingAs($admin)
            ->withSession(['_token' => csrf_token()])
            ->put("/dashboard/users/" . $user->id + 10, [
                'name' => 'test3 update',
                'email' => 'test3update@gmail.com',
            ])->assertStatus(404)
            ->assertSeeText(['404', 'Not Found']);
    }

    public function testUpdateAuthenticatedUserCannotDeleteOwnProfile()
    {
        $admin = User::where('role', 2)->first();

        $this->actingAs($admin)
            ->withSession(['_token' => csrf_token()])
            ->put("/dashboard/users/" . $admin->id, [
                'name' => 'test3 update',
                'email' => 'test3update@gmail.com',
            ])->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }

    public function testUpdateCannotUpdateAdminUser()
    {
        $sec_admin = User::factory()->create(['role' => 2]);
        $admin = User::where('role', 2)->first();

        $this->actingAs($admin)
            ->withSession(['_token' => csrf_token()])
            ->put("/dashboard/users/" . $sec_admin->id, [
                'name' => 'test3 update',
                'email' => 'test3update@gmail.com',
            ])->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }

    public function testUpdateAuthorization()
    {
        $user = User::where('role', 1)->first();
        $admin = User::where('role', 2)->first();

        $this->withSession(['_token' => csrf_token()])
            ->put("/dashboard/users/$admin->id", [
                'name' => 'test3 update',
                'email' => 'test3update@gmail.com'
            ])
            ->assertStatus(302)
            ->assertRedirect('/login');

        $this->actingAs($user)
            ->withSession(['_token' => csrf_token()])
            ->put("/dashboard/users/$admin->id", [
                'name' => 'test3 update',
                'email' => 'test3update@gmail.com'
            ])
            ->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }

    public function testDeleteSuccess()
    {
        $user = User::where('role', 1)->first();
        $admin = User::where('role', 2)->first();

        $this->actingAs($admin)
            ->withSession(['_token' => csrf_token()])
            ->delete("/dashboard/users/$user->id")
            ->assertStatus(302)
            ->assertRedirect('/dashboard/users')
            ->assertSessionHas('success', 'User successfully deleted.');
    }

    public function testDeleteNotFound()
    {
        $user = User::where('role', 1)->first();
        $admin = User::where('role', 2)->first();

        $this->actingAs($admin)
            ->withSession(['_token' => csrf_token()])
            ->delete("/dashboard/users/" . $user->id + 10)
            ->assertStatus(404)
            ->assertSeeText(['404', 'Not Found']);
    }

    public function testDeleteAuthenticatedUserCannotDeleteOwnProfile()
    {
        $admin = User::where('role', 2)->first();

        $this->actingAs($admin)
            ->withSession(['_token' => csrf_token()])
            ->delete('/dashboard/users/' . $admin->id)
            ->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }

    public function testDeleteCannotDeleteAdminUser()
    {
        $sec_admin = User::factory()->create(['role' => 2]);
        $admin = User::where('role', 2)->first();

        $this->actingAs($admin)
            ->withSession(['_token' => csrf_token()])
            ->delete('/dashboard/users/' . $sec_admin->id)
            ->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }

    public function testDeleteAuthorization()
    {
        $user = User::where('role', 1)->first();
        $admin = User::where('role', 2)->first();

        $this->withSession(['_token' => csrf_token()])
            ->delete("/dashboard/users/$admin->id")
            ->assertStatus(302)
            ->assertRedirect('/login');

        $this->actingAs($user)
            ->withSession(['_token' => csrf_token()])
            ->delete("/dashboard/users/$admin->id")
            ->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }
}
