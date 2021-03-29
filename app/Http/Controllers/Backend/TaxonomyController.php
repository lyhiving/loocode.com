<?php


namespace App\Http\Controllers\Backend;


use App\Attributes\Route;
use App\Http\Result;
use Corcel\Model\Tag;
use Corcel\Model\Taxonomy;
use Corcel\Model\Term;
use Corcel\Model\TermRelationship;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class CategoryController
 * @package App\Http\Controllers\Backend
 */
#[Route(title: "文章", icon: "file-text")]
class TaxonomyController extends BackendController
{

    /**
     * @return Result
     */
    #[Route(title: "分类目录", sort: 3, link: "/app/content/category")]
    public function category(): Result
    {
        return Result::ok();
    }

    /**
     * @return Result
     */
    #[Route(title: "标签", sort: 4, link: "/app/content/tag")]
    public function tag(): Result
    {
        return Result::ok();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    #[Route(title: "分类列表", parent: "分类目录")]
    public function categories(Request $request): Result
    {
        if ($request->query->has('name_like')) {
            $name = $request->query->get('name_like');
        } else {
            $name = $request->get('name');
        }
        $builder = Taxonomy::category();
        if ($name) {
            $builder = $builder->whereHas('term', function ($query) use ($name) {
                $query->where('slug', 'like', '%' . $name . '%');
            });
        }
        /**
         * @var $categories Collection
         */
        $categories = $builder->get();
        $result = [];
        if ($categories->count() > 0) {
            $children = [];
            foreach ($categories as $category) {
                if ($category->parent > 0) {
                    $children[$category->parent][] = $category->term_taxonomy_id;
                }
            }
            $result = $this->rows($categories, $children);
        }
        return Result::ok($result);
    }

    /**
     * @param Request $request
     * @return Result
     */
    #[Route(title: "标签列表", parent: "标签")]
    public function tags(Request $request): Result
    {
        if ($request->query->has('name_like')) {
            $name = $request->query->get('name_like');
        } else {
            $name = $request->get('name');
        }
        $tag = new Tag();
        if ($name) {
            $tag = $tag->whereHas('term', function ($query) use ($name) {
                $query->where('slug', 'like', '%' . $name . '%');
            });
        }
        $tags = $tag->orderBy('term_taxonomy_id', 'desc')
            ->paginate(
                $request->query->getInt("data_per_page", 30),
                ['*'],
                'data_current_page'
            );
        return Result::ok($tags);
    }

    /**
     * @param Collection $categories
     * @param array $children
     * @param int $parent
     * @param int $level
     * @param array $result
     * @return array
     */
    private function rows(Collection $categories, array $children, int $parent = 0, int $level = 0, array $result = []): array
    {
        foreach ($categories as $key => $taxonomy) {
            if ($taxonomy->parent != $parent) {
                continue;
            }
            $pad = str_repeat( '— ', max( 0, $level));
            $taxonomy->level = $level;
            $taxonomy->term->name = $pad . ' ' . $taxonomy->term->name;
            $result[] = $taxonomy;
            unset($categories[$key]);
            if (isset($children[$taxonomy->term_taxonomy_id])) {
                $result = $this->rows($categories, $children, $taxonomy->term_taxonomy_id, $level + 1, $result);
            }
        }
        return $result;
    }

    /**
     * @param Request $request
     * @return Result
     */
    #[Route(title: "创建分类", parent: "分类目录")]
    public function storeCategory(Request $request): Result
    {
        return $this->store($request);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return Result
     */
    #[Route(title: "更新分类", parent: "分类目录")]
    public function updateCategory(int $id, Request $request): Result
    {
        return $this->update($id, $request);
    }

    /**
     * @param int $id
     * @return Result
     */
    #[Route(title: "删除分类", parent: "分类目录")]
    public function deleteCategory(int $id): Result
    {
        return $this->delete($id);
    }

    /**
     * @param Request $request
     * @return Result
     */
    #[Route(title: "创建标签", parent: "标签")]
    public function storeTag(Request $request): Result
    {
        return $this->store($request);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return Result
     */
    #[Route(title: "更新标签", parent: "标签")]
    public function updateTag(int $id, Request $request): Result
    {
        return $this->update($id, $request);
    }

    /**
     * @param int $id
     * @return Result
     */
    #[Route(title: "删除标签", parent: "标签")]
    public function deleteTag(int $id): Result
    {
        return $this->delete($id);
    }

    /**
     * @param Request $request
     * @return Result
     */
    private function store(Request $request)
    {
        $body = $request->json()->all();
        if (empty($body['name'])) {
            return Result::err(500, "名称不能为空");
        }
        if (empty($body['slug'])) {
            $body['slug'] = $body['name'];
        }
        $term = Term::firstOrCreate(['name' => $body['name']], ['slug' => $body['slug']]);
        if ($term->taxonomy != null && $term->taxonomy->taxonomy == $body['taxonomy']) {
            return Result::ok(null, "添加成功");
        }
        $taxonomy = new Taxonomy();
        $taxonomy->taxonomy = $body['taxonomy'];
        $taxonomy->description = $body['description'] ?? "";
        $taxonomy->parent = $body['parent'] ?? 0;
        $term->taxonomy()->save($taxonomy);
        return Result::ok(null, "添加成功");
    }

    /**
     * @param int $id
     * @param Request $request
     * @return Result
     */
    private function update(int $id, Request $request): Result
    {
        $body = $request->json()->all();
        if (empty($body['name'])) {
            return Result::err(500, "名称不能为空");
        }
        if (empty($body['slug'])) {
            $body['slug'] = $body['name'];
        }
        $taxonomy = Taxonomy::find($id);
        if ($taxonomy == null) {
            return Result::err(404);
        }
        $taxonomy->description = $body['description'] ?? "";
        $taxonomy->parent = $body['parent'] ?? 0;
        $term = Term::find($taxonomy->term_id);
        $term->name = $body['name'];
        $term->slug = $body['slug'];
        $term->taxonomy()->save($taxonomy);
        $term->save();
        return Result::ok(null, "更新成功");
    }

    /**
     * @param int $id
     * @return Result|void
     */
    private function delete(int $id): Result
    {
        $taxonomy = Taxonomy::find($id);
        if ($taxonomy == null) {
            return Result::err(404, "资源不存在");
        }
        TermRelationship::where("term_taxonomy_id", $id)->delete();
        $taxonomy->delete();
        return Result::ok(null, "删除成功");
    }


}
