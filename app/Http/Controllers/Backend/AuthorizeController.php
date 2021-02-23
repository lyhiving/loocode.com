<?php
declare(strict_types=1);


namespace App\Http\Controllers\Backend;


use App\Http\Result;
use App\Services\Auth\JwtGuard;
use DateTimeImmutable;
use Illuminate\Auth\GenericUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;

class AuthorizeController
{

    /**
     * @param Request $request
     * @return Result
     */
    public function authenticate(Request $request): Result
    {
        $body = $request->json()->all();
        /**
         * @var $guard JwtGuard
         */
        $guard = Auth::guard("backend");
        $credentials = ['email' => $body['email'], 'password' => $body['password']];

        /**
         * @var $user GenericUser
         */
        $user = $guard->getProvider()->retrieveByCredentials($credentials);
        if ($user == null) {
            return Result::err(404, "用户不存在");
        }
        $isValid = $guard->getProvider()->validateCredentials($user, $credentials);
        if (!$isValid) {
            return Result::err(600, "密码匹配失败");
        }
        $config = Configuration::forSymmetricSigner(
            new Sha256(),
            Key\InMemory::file(base_path() . '/key.pem')
        );
        $now = new DateTimeImmutable();
        $token = $config->builder()
            ->issuedBy(config('app.url'))
            ->identifiedBy(explode(':', config('app.key'))[1])
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now->modify('+1 minute'))
            // Configures the expiration time of the token (exp claim)
            ->expiresAt($now->modify('+1 hour'))
            // Configures a new claim, called "uid"
            ->withClaim('id', $user->id)
            ->withClaim('email', $body['email'])
            ->withClaim('avatar', $user->avatar)
            ->getToken($config->signer(), $config->signingKey());
        return Result::ok(['token' => $token->toString()]);
    }
}