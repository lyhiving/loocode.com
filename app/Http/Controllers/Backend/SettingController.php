<?php


namespace App\Http\Controllers\Backend;

use App\Attributes\Route;
use App\Http\Result;
use Corcel\Model\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

#[Route(title: "设置", icon: "settings-2")]
class SettingController extends BackendController
{
    /**
     * @return Result
     */
    #[Route(title: "站点配置", link: "/app/system/configuration")]
    public function anchor(): Result
    {
        return Result::ok();
    }

    /**
     * @return Result
     */
    #[Route(title: "配置列表", parent: "站点配置")]
    public function options(): Result
    {
        $options = DB::table('options')->paginate(30);
        foreach ($options as $option) {
            $option->option_value = $this->formatConfigureValue($option->option_value);
            $option->type = 5;
            if (is_bool($option->option_value)) {
                $option->type = 1;
            }
            if (is_object($option->option_value)) {
                $option->type = 3;
            }
            if (is_array($option->option_value)) {
                $option->type = 2;
                if (is_object($option->option_value[0])) {
                    $option->type = 4;
                }
            }
            $option->description = "";
        }
        return Result::ok($options);
    }

    /**
     * @param Request $request
     * @return Result
     */
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
        Option::add($body->option_name, $body->option_value);
        return Result::ok(null, "创建成功");
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Result
     */
    public function update(Request $request, int $id): Result
    {
        $body = json_decode($request->getContent());
        $option = Option::find($id);
        if ($option == null) {
            return Result::err(600, "配置不存在");
        }
        $option->option_value = is_scalar($body->option_value) ? $body->option_value : json_encode($body->option_value);
        $option->update();
        return Result::ok(null, "更新成功");
    }

    /**
     * @param string $value
     * @return mixed
     */
    public function formatConfigureValue(string $value): mixed
    {
        if ($value === 'true') {
            $formatValue = true;
        } elseif ($value === 'false') {
            $formatValue = false;
        } elseif (in_array(substr($value, 0, 1), ['[', '{'])) {
            $formatValue = json_decode($value);
        } elseif (is_numeric($value)) {
            $formatValue = (int)$value;
        } else {
            $formatValue = $value;
        }
        return $formatValue;
    }



}
