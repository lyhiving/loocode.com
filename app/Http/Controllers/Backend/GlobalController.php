<?php


namespace App\Http\Controllers\Backend;

use App\Attributes\Route;
use App\Helpers\Helper;
use App\Http\Result;
use Corcel\Model\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class GlobalController
 * @package App\Http\Controllers\Backend
 */
#[Route(title: "设置", sort: 111, icon: "settings-2")]
class GlobalController extends BackendController
{
    /**
     * @return Result
     */
    #[Route(title: "全局", sort: 0, link: "/app/system/configuration")]
    public function anchor(): Result
    {
        return Result::ok();
    }

    /**
     * @return Result
     */
    #[Route(title: "配置列表", parent: "全局")]
    public function options(): Result
    {
        $options = DB::table('options')->paginate(30);
        foreach ($options as $option) {
            $value = Helper::formatValue($option->option_value);
            $option->type = 5;
            if (is_bool($value)) {
                $option->type = 1;
            }
            if (is_object($value)) {
                $option->type = 3;
                $option->option_value = $value;
            }
            if (is_array($value)) {
                $option->type = 2;
                if (is_object($value[0])) {
                    $option->type = 4;
                }
                $option->option_value = $value;
            }
            $option->description = "";
        }
        return Result::ok($options);
    }

    /**
     * @param Request $request
     * @return Result
     */
    #[Route(title: "添加配置", parent: "全局")]
    public function store(Request $request): Result
    {
        $body = json_decode($request->getContent());
        if (empty($body->option_name)) {
            return Result::err(600, "名称不能为空");
        }
        $item = Option::get($body->option_name);
        if ($item) {
            return Result::err(603, "已存在相同名称配置");
        }
        Option::add($body->option_name, is_scalar($body->option_value) ? $body->option_value : json_encode($body->option_value));
        return Result::ok(null, "创建成功");
    }

    /**
     * @param Option $option
     * @param Request $request
     * @return Result
     */
    #[Route(title: "更新配置", parent: "全局")]
    public function update(Option $option, Request $request): Result
    {
        $body = json_decode($request->getContent());
        $option->option_value = is_scalar($body->option_value) ? $body->option_value : json_encode($body->option_value);
        $option->update();
        return Result::ok(null, "更新成功");
    }

}
