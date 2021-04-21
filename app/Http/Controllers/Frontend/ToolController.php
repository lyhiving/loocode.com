<?php
declare(strict_types=1);

namespace App\Http\Controllers\Frontend;


use App\Http\Result;
use FastFFI\LAC\LAC;
use FastFFI\Opencc\OpenCC;
use FastFFI\Pinyin\Pinyin;
use Illuminate\Http\Request;

/**
 * Class ToolController
 * @package App\Http\Controllers\Frontend
 */
class ToolController extends FrontendController
{
    private $tools = [
        'pinyin' => [
            'name' => '汉字拼音',
            'href' => '/tool/pinyin/chinese-to-pinyin',
            'seo' => [
                'title' => '在线汉字转拼音_汉字音标',
                'keywords' => '汉字汉语转拼单，汉字音标，汉字首字母, 中文转拼音, 中文转音标',
                'description' => '在线的中文转拼音，支持多种转换',
            ],
        ],
        'opencc' => [
            'name' => '简体繁体',
            'href' => '/tool/opencc/simplified-chinese-to-traditional-chinese',
            'seo' => [
                'title' => '在线简体转繁体_繁体转简体',
                'keywords' => '简体转繁体，简体转台湾繁体，简体转香港繁体，繁体转简体，繁体转中文简体，opencc在线测试',
                'description' => '在线中文简体转繁体，繁体转中文简体，支持多种繁体转换',
            ],
        ],
        'lac' => [
            'name' => '中文分词',
            'href' => '/tool/lac/chinese-word-segmentation',
            'seo' => [
                'title' => '在线中文分词_百度LAC分词',
                'keywords' => '中文分词，中文在线分词，在线百度LAC分词，分词权重，在线智能分词, 百度lac在线测试',
                'description' => '在线中文智能分词，百度LAC智能分词测试',
            ],
        ],
    ];

    static array $tags = [
        'n' => '普通名词', 'f' => '方位名词', 's' => '处所名词', 'nw' => '作品名',
        'nz' => '其他专名', 'v' => '普通动词', 'vd' => '动副词', 'vn' => '名动词',
        'a' => '形容词', 'ad' => '副形词', 'an' => '名形词', 'd' => '副词',
        'm' => '数量词', 'q' => '量词', 'r' => '代词', 'p' => '介词',
        'c' => '连词', 'u' => '助词', 'xc' => '其他虚词', 'w' => '标点符号',
        'PER' => '人名', 'LOC' => '地名', 'ORG' => '机构名', 'TIME' => '时间',
    ];

    static array $weight = [
        0 => '表述的冗余词', 1 => '限定较弱的词', 2 => '强限定的词', 3 => '核心词',
    ];

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        return view($this->theme . '/tools/index', [
            'seo' => $this->getSeo(
                '常用在线工具',
                '汉语转拼音，汉字转拼音，中文简体转繁体，繁体转简体，中文智能分词',
                '常用在线测试工具'
            ),
            'tools' => $this->tools,
        ]);
    }

    /**
     * @param Request $request
     * @param string $name
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function tool(Request $request)
    {
        $name = explode('/', $request->getPathInfo())[2];
        $tool = $this->tools[$name];
        return view($this->theme . '/tools/' . $name, [
            'seo' => $this->getSeo(
                $tool['seo']['title'],
                $tool['seo']['keywords'],
                $tool['seo']['description'],
            ),
            'tools' => $this->tools,
            'tool' => $tool,
            'name' => $name,
        ]);
    }

    /**
     * @param Request $request
     * @return Result|false|mixed
     */
    public function convert(Request $request)
    {
        /**
         * @var $tool 'pinyin' | 'opencc' | 'lac'
         */
        $tool = $request->post('tool');
        $body = $request->post();
        if (method_exists($this, $tool)) {
            return call_user_func([$this, $tool], $body);
        }
        return Result::ok();
    }

    /**
     * @param array $body
     * @return Result
     */
    private function pinyin(array $body)
    {
        if (empty($body['text'])) {
            return Result::err();
        }
        $text = $body['text'];
        $mode = (int)$body['mode'];
        $obj = Pinyin::new();
        $r = "";
        switch ($mode) {
            case 1:
                $r = $obj->tone($text);
                break;
            case 2:
                $r = $obj->plain($text);
                break;
            case 3:
                $r = $obj->letter($text);
                break;
            case 4:
                $r = $obj->tone_multi($text);
                break;
        }
        return Result::ok($r);
    }


    /**
     * @param array $body
     * @return Result
     */
    private function opencc(array $body)
    {
        if (empty($body['text'])) {
            return Result::err();
        }
        $text = $body['text'];
        $mode = (int)$body['mode'];
        $obj = OpenCC::new();
        $r = "";
        switch ($mode) {
            case 1:
                $r = $obj->s2t($text);
                break;
            case 2:
                $r = $obj->t2s($text);
                break;
            case 3:
                $r = $obj->s2tw($text);
                break;
            case 4:
                $r = $obj->tw2s($text);
                break;
            case 5:
                $r = $obj->s2hk($text);
                break;
            case 5:
                $r = $obj->hk2s($text);
                break;
        }
        return Result::ok($r);
    }


    /**
     * @param array $body
     * @return Result
     */
    private function lac(array $body)
    {
        if (empty($body['text'])) {
            return Result::err();
        }
        $text = $body['text'];
        $mode = (int)$body['mode'];
        $model = "seg_model";
        switch ($mode) {
            case 2:
                $model = "lac_model";
                break;
            case 3:
                $model = "rank_model";
                break;
        }
        $path = resource_path("model/" . $model);
        $obj = LAC::new($path);
        $result = $obj->parse($text);
        $table = $thead = "";
        if (!empty($result['words'])) {
            $words = explode(" ", $result['words']);
            $tags = $weight = [];
            if (!empty($result['tags'])) {
                $tags = explode(" ", $result['tags']);
            }
            if ($mode == 3) {
                $weight = explode(" ", $result['weight']);
            }
            $tr = "";
            unset($words[count($words) - 1]);
            foreach ($words as $key => $word) {
                if (($mode < 3 && ($key + 1) % ($mode == 1 ? 4 : 2) == 1) || $mode == 3) {
                    $tr .= '<tr>';
                }
                $tr .= sprintf('<td class="text-center border px-4 py-2 text-gray-600 font-medium">%s</td>', $word);
                if ($mode > 1) {
                    $tr .= sprintf(
                        '<td class="text-center border px-4 py-2 text-gray-600 font-medium">%s</td>',
                        self::$tags[$tags[$key]] ?? '',
                    );
                    if ($mode == 3) {
                        $tr .= sprintf(
                            '<td class="text-center border px-4 py-2 text-gray-600 font-medium">%s</td>',
                            self::$weight[$weight[$key]] ?? "无",
                        );
                    }
                }
                if (($mode < 3 && ($key + 1) % ($mode == 1 ? 4 : 2) == 0) || $mode == 3) {
                    $tr .= '</tr>';
                }
            }
            $th = "";
            if ($mode > 1) {
                $th = '<th>词</th><th>标注</th>';
                if ($mode == 3) {
                    $th .= '<th>权重</th>';
                } else {
                    $th .= $th;
                }
            }
            if ($th) {
                $thead = <<<EOF
 <thead>
    <tr>
        $th
    </tr>
  </thead>
EOF;
            }
            $table = <<<EOF
<table class="table-auto w-full">
  $thead
  <tbody>
       $tr
  </tbody>
</table>
EOF;
        }
        return Result::ok($table);
    }


}
