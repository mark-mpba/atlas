<?php

namespace Modules\Users\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Users\Models\User;
use Tests\TestCase;

/**
 * Class UserTest
 */
class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * It can create a user.
     *
     * @return void
     */
    public function test_it_can_create_a_user(): void
    {
        $user = User::query()->create([
            'name' => 'Mark Gregory',
            'email' => 'mark@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->assertNotNull($user->id);

        $this->assertDatabaseHas(User::TABLE_NAME, [
            'email' => 'mark@example.com',
            'name' => 'Mark Gregory',
        ]);
    }

    /**
     * It hashes the password when cast as hashed.
     *
     * @return void
     */
    public function test_it_hashes_the_password(): void
    {
        $user = User::query()->create([
            'name' => 'Mark Gregory',
            'email' => 'hash@example.com',
            'password' => 'password',
        ]);

        $this->assertNotSame('password', $user->password);
        $this->assertTrue(password_verify('password', $user->password));
    }

    /**
     * It can store Fortify two factor columns.
     *
     * @return void
     */
    public function test_it_can_store_two_factor_columns(): void
    {
        $user = User::query()->create([
            'name' => 'Mark Gregory',
            'email' => '2fa@example.com',
            'password' => 'password',
        ]);

        $user->update([
            'two_factor_secret' => 'secret-value',
            'two_factor_recovery_codes' => json_encode(['code-1', 'code-2']),
            'two_factor_confirmed_at' => now(),
        ]);

        $user->refresh();

        $this->assertSame('secret-value', $user->two_factor_secret);
        $this->assertSame(json_encode(['code-1', 'code-2']), $user->two_factor_recovery_codes);
        $this->assertNotNull($user->two_factor_confirmed_at);
    }

    /**
     * It requires a unique email address.
     *
     * @return void
     */
    public function test_it_requires_a_unique_email_address(): void
    {
        User::query()->create([
            'name' => 'Mark Gregory',
            'email' => 'unique@example.com',
            'password' => 'password',
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        User::query()->create([
            'name' => 'Another Mark',
            'email' => 'unique@example.com',
            'password' => 'password',
        ]);
    }
}
