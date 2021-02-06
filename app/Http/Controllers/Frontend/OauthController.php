<?php
declare(strict_types=1);

namespace App\Http\Controllers\Frontend;


use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\GenericUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

/**
 * Class OauthController
 * @package App\Http\Controllers\Frontend
 */
class OauthController extends Controller
{

    /**
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        Auth::logout();
        return redirect("/");
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
            return redirect("/");
        }
        $id = $user->getId();
        $sql = 'SELECT id, user_id FROM social_accounts WHERE provider = ? AND provider_id = ? LIMIT 1';
        $account = DB::selectOne($sql, [$endpoint, $id]);
        $now = now();
        if ($account) {
            DB::table('social_accounts')->where([
                'provider' => $endpoint, 'provider_id' => $id,
            ])->update([
                'token' => $user->token,
                'avatar' => $user->getAvatar(),
                'updated_at' => $now,
            ]);
            DB::table('users')->where('id', '=', $account->user_id)->update([
                'name' => $user->getNickname(),
                'avatar' => $user->getAvatar(),
                'updated_at' => $now,
            ]);
            $userId = $account->user_id;
        } else {
            $userId = DB::table('users')->insertGetId([
                'name' => $user->getNickname(),
                'email' => $user->getEmail(),
                'password' => password_hash((string) $id, PASSWORD_BCRYPT),
                'confirmation_code' => Str::random(6),
                'active' => 1,
                'confirmed' => 1,
                'avatar' => $user->getAvatar(),
                'created_at'=> $now,
                'updated_at' => $now
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
        $user = DB::table('users')->where('id', '=', $userId)->first();
        Auth::login(new GenericUser((array) $user), false);
        return redirect($redirectUrl);
    }
}
