<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use stdClass;

class Controller extends BaseController
{
    // use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var array
     */
    static array $options = [];

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        if (empty(self::$options)) {
            self::$options = $this->getSystemOptions();
        }
    }

    /**
     * @param string $t
     * @param string $k
     * @param string $d
     * @return stdClass
     */
    protected function getSeo(string $t = '', string $k = '', string $d = ''): stdClass
    {
        $title = $t ? $t . ' - ' . self::$options['site_title']
                    : self::$options['site_title'] . ' - ' . self::$options['site_append_title'];

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
SELECT post_id, post_key, post_value FROM postmeta WHERE post_id IN ($bindParamStr)
EOF;
        $metas = DB::select($sql, $objectIdSets);
        $sql = <<<EOF
SELECT t1.taxonomy, t2.object_id, t3.slug FROM term_taxonomy as t1
    LEFT JOIN term_relation as t2 ON (t1.taxonomy_id=t2.term_taxonomy_id)
LEFT JOIN terms as t3 ON (t1.term_id=t3.id)
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
                [$item->post_key => $item->post_value]
            );
        }
        return [$postMeta, $postTag];
    }
}
