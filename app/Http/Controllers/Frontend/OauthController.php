<?php
declare(strict_types=1);

namespace App\Http\Controllers\Frontend;


use Exception;
use Illuminate\Auth\GenericUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

/**
 * Class OauthController
 * @package App\Http\Controllers\Frontend
 */
class OauthController
{

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $redirectUri = $request->query->get('redirect');
        if (empty($redirectUri)) {
            $redirectUri = $request->server->get('HTTP_REFERER');
        }
        return redirect($redirectUri ? : '/');
    }

    /**
     * @param Request $request
     * @param string $endpoint
     * @return mixed
     */
    public function endpoint(Request $request, string $endpoint): RedirectResponse
    {
        if (($referer = $request->server('HTTP_REFERER'))) {
            $request->session()->put('referer', $referer);
        }
        return Socialite::driver($endpoint)
            ->redirect();
    }

    /**
     * @param Request $request
     * @param string $endpoint
     * @return mixed
     */
    public function callback(Request $request, string $endpoint): RedirectResponse
    {
        $driver = Socialite::driver($endpoint);
        try {
            $user = $driver->user();
        } catch (Exception $exception) {
            info($exception->getMessage());
            return redirect("/");
        }
        $id = $user->getId();
        $sql = 'SELECT id, user_id FROM social_accounts WHERE provider = ? AND provider_id = ? LIMIT 1';
        $account = DB::selectOne($sql, [$endpoint, $id]);
        $now = now();
        $name = $user->getName();
        $nickname = $user->getNickname();
        if ($account) {
            DB::table('social_accounts')->where([
                'provider' => $endpoint, 'provider_id' => $id,
            ])->update([
                'token' => $user->token,
                'avatar' => $user->getAvatar(),
                'updated_at' => $now,
            ]);
            DB::table('users')->where('id', '=', $account->user_id)->update([
                'user_nicename' => $nickname,
                'display_name' => $nickname,
                'avatar' => $user->getAvatar(),
            ]);
            $userId = $account->user_id;
        } else {

            $userId = DB::table('users')->insertGetId([
                'user_login' => $name ? : $nickname,
                'user_pass' => Hash::make(Str::random(8)),
                'user_nicename' => $nickname,
                'user_email' => $user->getEmail(),
                'user_registered' => $now,
                'display_name' => $nickname,
                'avatar' => $user->getAvatar(),
            ]);
            if ($userId) {
                DB::table('social_accounts')->insert([
                    'user_id' => $userId,
                    'provider'=> $endpoint,
                    'provider_id' => $id,
                    'token' => $user->token,
                    'avatar'=> $user->getAvatar(),
                    'created_at' => $now,
                    'updated_at' => $now
                ]);
            }
        }
        $session = $request->session();
        $redirectUrl = "/";
        if (($url = $session->get('referer'))) {
            $redirectUrl = $url;
        }
        $user = DB::table('users')->select([
            'ID AS id', 'display_name AS name', 'user_pass AS password', 'user_email AS email', 'avatar'
        ])->where('id', '=', $userId)->first();
        Auth::login(new GenericUser((array) $user), false);
        return redirect($redirectUrl);
    }
}
