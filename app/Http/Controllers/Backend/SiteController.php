<?php
declare(strict_types=1);

namespace App\Http\Controllers\Backend;


use App\Attributes\Route;
use App\Helpers\Helper;
use App\Http\Result;
use Corcel\Model\Option;
use Illuminate\Http\Request;

/**
 * Class SiteController
 * @package App\Http\Controllers\Backend
 */
#[Route(title: "设置", sort: 111, icon: "settings-2")]
class SiteController extends BackendController
{
    private array $defaultGeneralNames = [
        'site_title',
        'site_append_title',
        'site_url',
        'site_static_url',
        'site_description',
        'site_keyword',
        'timezone'
    ];
    private array $defaultAdNames = [
        'google_ad',
        'baidu_ad',
        'google_ad_open',
        'baidu_ad_open',

        'baidu_analysis',
        'google_analysis',
        'cnzz_analysis',
    ];

    /**
     * @return Result
     */
    #[Route(title: "站点", sort: 0, link: "/app/system/site")]
    public function anchor(): Result
    {
        return Result::ok();
    }

    /**
     * @return Result
     */
    #[Route(title: "配置信息", parent: "站点")]
    public function options(): Result
    {
        $option = Option::asArray($this->defaultGeneralNames);
        if (empty($option['timezone'])) {
            $option['timezone'] = date_default_timezone_get();
        }
        $data = new \stdClass();
        $data->timezone = timezone_identifiers_list();
        $data->option = $option;
        return Result::ok($data);
    }

    /**
     * @param Request $request
     * @return Result
     */
    #[Route(title: "保存配置", parent: "站点")]
    public function saveGeneral(Request $request): Result
    {
        return $this->save($request, $this->defaultGeneralNames);
    }

    /**
     * @return Result
     */
    #[Route(title: "广告统计配置", parent: "站点")]
    public function adOptions(): Result
    {
        $options = Option::asArray($this->defaultAdNames);
        foreach ($this->defaultAdNames as $name) {
            if (!isset($options[$name])) {
                $options[$name] = null;
            } else {
                $options[$name] = Helper::formatValue($options[$name]);
            }
        }
        return Result::ok($options);
    }

    /**
     * @param Request $request
     * @return Result
     */
    #[Route(title: "保存广告统计配置", parent: "站点")]
    public function saveAd(Request $request): Result
    {
        return $this->save($request, $this->defaultAdNames);
    }

    /**
     * @param Request $request
     * @param array $options
     * @return Result
     */
    private function save(Request $request, array $options): Result
    {
        $body = json_decode($request->getContent(), true);
        foreach ($options as $optionName) {
            if (isset($body[$optionName])) {
                Option::updateOrCreate(['option_name' => $optionName], [
                    'option_value' =>
                        is_bool($body[$optionName])
                            ? [true => 'true', false => 'false'][$body[$optionName]]
                            : (is_array($body[$optionName]) ? json_encode($body[$optionName]) : $body[$optionName]),
                ]);
            }
        }
        return Result::ok(null, "创建成功");
    }
}
