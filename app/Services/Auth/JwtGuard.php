<?php


namespace App\Services\Auth;


use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class JwtGuard implements Guard
{

    /**
     * @var UserProvider
     */
    private UserProvider $provider;

    public function __construct(
        UserProvider $provider,
        Request $request
    )
    {
        $this->provider = $provider;
    }

    public function check()
    {
        // TODO: Implement check() method.
    }

    public function guest()
    {
        // TODO: Implement guest() method.
    }

    public function user()
    {
        // TODO: Implement user() method.
    }

    public function id()
    {
        // TODO: Implement id() method.
    }

    public function validate(array $credentials = [])
    {
        // TODO: Implement validate() method.
    }

    public function setUser(Authenticatable $user)
    {
        // TODO: Implement setUser() method.
    }

    /**
     * @return UserProvider
     */
    public function getProvider(): UserProvider
    {
        return $this->provider;
    }
}
