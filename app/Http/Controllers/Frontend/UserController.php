<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;


use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class UserController extends FrontendController
{

    /**
     * @return Application|Factory|View
     */
    public function setting(): View
    {
        return view('user/setting', [
            'seo' => $this->getSeo(),
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function upload(Request $request): JsonResponse
    {
        $files = $request->allFiles();
        $data = ['code' => 500, 'message' => 'error', 'data' => null];
        $response = new JsonResponse($data);
        if (count($files) < 1 || ($user = $request->user()) == null) {
            return $response;
        }
        $name = array_key_first($files);
        $file = $files[$name];
        if ($file->getSize() > 1024 * 1024) {
            $data['message'] = "超出文件大小";
            return $response->setData($data);
        }
        if (empty($file->getPath()) || !in_array($file->guessExtension(), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'])) {
            $data['message'] = "mime类型错误";
            return $response->setData($data);
        }
        $path = 'images/' . date('ym');
        $filename = md5($file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();
        $url = $file->storePubliclyAs($path, $filename, 'public');
        $data['code'] = 200;
        $data['message'] = 'success';
        $data['data'] = Storage::disk('public')->url($url);
        switch ($name) {
            case 'alipayQr':
            case 'wechatQr':
                DB::statement(
                    'INSERT INTO usermeta (user_id, meta_key, meta_value) VALUE (?, ?, ?) ON DUPLICATE KEY UPDATE `meta_value` = ?',
                    [$user->id, $name, $url, $url]
                );
                break;
            case 'avatar':
                DB::table('users')->where('id', '=', $user->id)->update([
                    'avatar' => $url,
                ]);
                break;
        }
        return $response->setData($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $data = ['code' => 200, 'message' => '', 'data' => null];
        $meta = $request->request->all();
        $user = $request->user();
        $response = new JsonResponse($data);
        if ($user == null || empty($meta)) {
            return $response;
        }
        $map = ['email' => 'user_email'];
        $value = [];
        foreach ($map as $key => $v) {
            if (isset($meta[$key])) {
                $value[$v] = $meta[$key];
            }
        }
        DB::table('users')->where('id', '=', $user->id)->update($value);
        return $response;
    }
}
