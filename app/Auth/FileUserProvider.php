<?php

namespace App\Auth;

use App\FileModel\FileModel;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class FileUserProvider implements UserProvider
{
    protected $model;

    /**
     * @var Hasher
     */
    protected $hasher;

    public function __construct(Hasher $hasher, $model)
    {
        $this->hasher = $hasher;
        $this->model = $model;
    }

    /**
     * @return FileModel
     */
    public function createModel()
    {
        $class = '\\'.ltrim($this->model, '\\');

        return new $class;
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        return $this->createModel()->find($identifier);
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed   $identifier
     * @param  string  $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        $model = $this->createModel();

        return $model->get([
            $model->getAuthIdentifierName() => $identifier,
            $model->getRememberTokenName() => $token,
        ])->first();
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string  $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        $user->setRememberToken($token);
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        foreach ($credentials as $key => $value) {
            if (Str::contains($key, 'password')) {
                Arr::forget($credentials, $key);
            }
        }

        return $this->createModel()->get($credentials)->first();
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $password = $credentials['password'];

        return $this->hasher->check($password, $user->getAuthPassword());
    }
}