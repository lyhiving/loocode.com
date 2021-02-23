<?php


namespace App\Listeners;


use App\Events\Posts;
use App\Models\PostMeta;
use App\Models\TermRelation;
use App\Models\TermTaxonomy;
use GuzzleHttp\Client;

class PostsListener
{
    public function handle(Posts $event)
    {
        if ($event->relationIds) {
            foreach ($event->relationIds as $taxonomyId) {
                $relation = new TermRelation();
                $relation->object_id = $event->postId;
                $relation->term_taxonomy_id = $taxonomyId;
                $relation->term_order = 0;
                try {
                    $relation->saveOrFail();
                    TermTaxonomy::where('taxonomy_id', $taxonomyId)->increment('count', 1);
                }catch (\Throwable $exception) {

                }
            }
        }
        if ($event->meta) {
            foreach ($event->meta as $key => $value) {
                if (empty($key)) {
                    continue;
                }
                $first = PostMeta::where('post_id', $event->postId)->where('post_key', $key)->first();
                if ($first == null) {
                    $first = new PostMeta;
                }
                $first->post_id = $event->postId;
                $first->post_key = $key;
                $first->post_value = $value == null ? '' : $value;
                $first->save();
            }
        }

        if ($event->submitType == 'create') {
            $token = config('app.baidu_site_token');
            $uri = 'http://data.zz.baidu.com/urls?appid=1594785281517469&token=' . $token . '&type=realtime';
            $links = 'https://loocode.com/post/' . $event->postId;

            $http = new Client();
            $response = $http->post($uri, [
                'header' => [
                    'Content-Type' => 'text/plain',
                ],
                'body'   => implode("\n", [$links])
            ]);
            info($response->getBody());
        }
    }

}
