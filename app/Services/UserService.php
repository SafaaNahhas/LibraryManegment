<?php

namespace App\Services;

use Exception;
use App\Models\User;
use App\Services\ApiResponseService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserService
{
    /**
     * Get all users.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllUsers()
    {
        return User::all();
    }

    /**
     * Get a user by ID.
     *
     * @param int $id The ID of the user to retrieve.
     * @return \App\Models\User
     */
    public function getUserById($id)
    {
        $user = User::find($id);

        return $user;

    }

    /**
     * Create a new user.
     *
     * @param array $data The user data to create.
     * @return \App\Models\User
     */
    public function createUser($data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);
    }

    /**
     * Update an existing user.
     *
     * @param int $id The ID of the user to update.
     * @param array $data The user data to update.
     * @return \App\Models\User
     */
    public function updateUser($id, $data)
    {

        $user = User::find($id);

        if (!$user) {
            // throw new Exception('User not found');
            throw new Exception(ApiResponseService::error('User not found'));

        }

        $user->update($data);

        return $user;
    }

    /**
     * Delete a user.
     *
     * @param int $id The ID of the user to delete.
     * @return void
     */
    public function deleteUser($id)
    {

        $user = User::find($id);

        if (!$user) {
            throw new Exception('User not found');
        }

        $user->delete();
    }
}
