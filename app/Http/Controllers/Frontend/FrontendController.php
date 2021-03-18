<?php
declare(strict_types=1);


namespace App\Http\Controllers\Frontend;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use stdClass;

class FrontendController extends Controller
{

    /**
     * @var array
     */
    static array $options = [];

    public function __construct()
    {
        parent::__construct();

    }

    /**
     * @param string $t
     * @param string $k
     * @param string $d
     * @return stdClass
     */
    protected function getSeo(string $t = '', string $k = '', string $d = ''): stdClass
    {
        if (empty(self::$options)) {
            self::$options = $this->getSystemOptions();
        }
        $adOpen = self::$options['ad_open'] ?? "off";
        View::share([
            'static_domain' => config('app.asset_url'),
            'links' => [],
            'user' => request()->user(),
            'menu' => [],
            'options' => [
                'site_url' => self::$options['site_url'] ?? "",
                'site_title' => self::$options['site_title'] ?? "",
                'ad_value' => $adOpen !== 'off' ? (self::$options[$adOpen . '_ad'] ?? '') : ''
            ],
        ]);
        $title = $t ? $t . ' - ' . self::$options['site_title']
            : self::$options['site_title'] .
            (self::$options['site_append_title'] ? ' - ' . self::$options['site_append_title'] : '');
        $seo = new stdClass();
        $seo->title = $title;
        $seo->keyword = $k ? : self::$options['site_keyword'];
        $seo->description = $d ? : self::$options['site_description'];
        return $seo;
    }

    /**
     * @return array
     */
    protected function getSystemOptions(): array
    {
        $options = DB::select('SELECT option_name, option_value FROM options');
        $arr = [];
        foreach ($options as $item) {
            $arr[$item->option_name] = $item->option_value;
        }
        return $arr;
    }

    /**
     * @param array $objectIdSets
     * @return array[]
     */
    protected function getIdSetsMetesAndTaxonomy(array $objectIdSets): array
    {
        $bindParamStr = rtrim(str_repeat('?,', count($objectIdSets)), ',');
        $sql = <<<EOF
SELECT post_id, meta_key, meta_value FROM postmeta WHERE post_id IN ($bindParamStr)
EOF;
        $metas = DB::select($sql, $objectIdSets);
        $sql = <<<EOF
SELECT t1.taxonomy, t2.object_id, t3.slug FROM term_taxonomy as t1
    LEFT JOIN term_relationships as t2 ON (t1.term_taxonomy_id=t2.term_taxonomy_id)
LEFT JOIN terms as t3 ON (t1.term_id=t3.term_id)
WHERE t2.object_id IN ($bindParamStr)
EOF;
        $taxonomies = DB::select($sql, $objectIdSets);
        $postTag = $postMeta = [];
        foreach ($taxonomies as $item) {
            if ($item->taxonomy == "post_tag") {
                $postTag[$item->object_id] = array_merge($postTag[$item->object_id] ?? [], [$item->slug]);
            }
        }
        foreach ($metas as $item) {
            $postMeta[$item->post_id] = array_merge(
                $postMeta[$item->post_id] ?? [],
                [$item->meta_key => $item->meta_value]
            );
        }
        return [$postMeta, $postTag];
    }
}
