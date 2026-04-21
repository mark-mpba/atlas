<?php

namespace Modules\Users\Services;

use Illuminate\Support\Facades\Hash;
use Modules\Users\Models\User;

/**
 * Class UserService
 */
class UserService
{
    /**
     * Create a user.
     *
     * @param array<string, mixed> $data
     * @return User
     */
    public function create(array $data): User
    {
        return User::query()->create([
            'name' => (string) $data['name'],
            'email' => (string) $data['email'],
            'password' => Hash::make((string) $data['password']),
        ]);
    }
}
