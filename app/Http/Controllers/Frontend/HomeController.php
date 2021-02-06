<?php
declare(strict_types=1);

namespace App\Http\Controllers\Frontend;


use App\Http\Controllers\Controller;
use Illuminate\Container\Container;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Class HomeController
 * @package App\Http\Controllers\Frontend
 */
class HomeController extends Controller
{

    /**
     * @param Request $request
     * @return View|Factory
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function index(Request $request): View
    {
        $sql = <<<EOF
SELECT
    COUNT(t1.id) AS cnt
FROM posts as t1 LEFT JOIN users as t2 ON(t1.post_author=t2.id)
WHERE t1.post_status = 0 AND t1.post_type != 'menu' ORDER BY t1.post_modified DESC, t1.id DESC
EOF;
        $total = DB::selectOne($sql)->cnt ?? 0;
        $items = [];
        $currentPage = Paginator::resolveCurrentPage('p');
        $prePage = 30;
        if ($total > 0) {
            $sql = <<<EOF
    SELECT
        t1.id, t1.post_title, t1.post_author, t1.post_modified, t2.name, t2.avatar, t1.post_excerpt, t1.post_type
    FROM posts as t1 LEFT JOIN users as t2 ON(t1.post_author=t2.id)
    WHERE t1.post_status = 0 AND t1.post_type != 'menu' ORDER BY t1.post_modified DESC, t1.id DESC LIMIT ?, ?
    EOF;
            $items = DB::select($sql, [$currentPage * $prePage - $prePage, $prePage]);
            if ($items) {
                $objectIdSets = array_map(function ($item) {return $item->id;}, $items);
                [$postMeta, $postTag] = $this->getIdSetsMetesAndTaxonomy($objectIdSets);
                foreach ($items as  $item) {
                    $item->tags = $postTag[$item->id] ?? [];
                    $item->metas = $postMeta[$item->id] ?? [];
                }
            }
        }
        $paginator = Container::getInstance()->makeWith(
            LengthAwarePaginator::class,
            [
                'items' => $items,
                'total' => $total,
                'perPage' => $prePage,
                'currentPage' => $currentPage,
                'options' => [
                    'path' => Paginator::resolveCurrentPath(),
                    'pageName' => 'p',
                ]
            ]
        );
        return view('index', [
            'posts' => $paginator,
            'hotPosts' => $this->getHot(),
            'seo'   => $this->getSeo(),
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function search(Request $request): RedirectResponse
    {
        $q = $request->query('q');
        $url = 'https://www.google.com/search?q=site:%s %s';
        return redirect(sprintf($url, $request->getHttpHost(), $q));
    }

    /**
     * @return array
     */
    private function getHot(): array
    {
        $sql = <<<EOF
SELECT post_id FROM postmeta WHERE post_key = ? ORDER BY post_value DESC LIMIT 10
EOF;
        $result = DB::select($sql, ['_lc_post_views']);
        if (empty($result)) {
            return [];
        }
        $idSets = [];
        foreach ($result as $item) {
            $idSets[] = $item->post_id;
        }
        $bindParamStr = rtrim(str_repeat('?,', count($idSets)), ',');
        $sql = <<<EOF
SELECT id, post_title, post_type FROM posts WHERE id IN ({$bindParamStr})
EOF;
        return DB::select($sql, $idSets);
    }
}
