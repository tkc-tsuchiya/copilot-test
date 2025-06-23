<?php

namespace App\Providers;

use App\Models\JsonUser;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;

class JsonUserProvider implements UserProvider
{
    private $usersFile;

    public function __construct()
    {
        $this->usersFile = storage_path('app/users.json');
    }

    private function getUsers()
    {
        if (!file_exists($this->usersFile)) {
            return [];
        }
        
        $content = file_get_contents($this->usersFile);
        return json_decode($content, true) ?: [];
    }

    public function retrieveById($identifier)
    {
        $users = $this->getUsers();
        
        foreach ($users as $user) {
            if ($user['username'] === $identifier) {
                return new JsonUser($user['username'], $user['password']);
            }
        }
        
        return null;
    }

    public function retrieveByToken($identifier, $token)
    {
        // Not implemented for JSON storage
        return null;
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // Not implemented for JSON storage
    }

    public function retrieveByCredentials(array $credentials)
    {
        if (!isset($credentials['username'])) {
            return null;
        }

        $users = $this->getUsers();
        
        foreach ($users as $user) {
            if ($user['username'] === $credentials['username']) {
                return new JsonUser($user['username'], $user['password']);
            }
        }
        
        return null;
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        if (!isset($credentials['password'])) {
            return false;
        }

        return Hash::check($credentials['password'], $user->getAuthPassword());
    }

    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false)
    {
        // Not implemented for JSON storage
        return null;
    }
}