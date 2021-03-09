<?php
declare(strict_types=1);

namespace App\Listeners;


use App\Events\Post;
use Corcel\Model\Meta\PostMeta;
use Corcel\Model\Taxonomy;
use Corcel\Model\TermRelationship;
use GuzzleHttp\Client;

/**
 * Class PostListener
 * @package App\Listeners
 */
class PostListener
{

    /**
     * @param Post $event
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(Post $event)
    {
        if ($event->relationIds) {
            foreach ($event->relationIds as $taxonomyId) {
                $relation = new TermRelationship();
                $relation->object_id = $event->postId;
                $relation->term_taxonomy_id = $taxonomyId;
                $relation->term_order = 0;
                try {
                    $relation->saveOrFail();
                    Taxonomy::where('taxonomy_id', $taxonomyId)->increment('count', 1);
                }catch (\Throwable $exception) {
                    logger($exception->getTraceAsString());
                }
            }
        }
        if ($event->meta) {
            foreach ($event->meta as $key => $value) {
                if (empty($key)) {
                    continue;
                }
                $first = PostMeta::where('post_id', $event->postId)->where('meta_key', $key)->first();
                if ($first == null) {
                    $first = new PostMeta;
                }
                $first->post_id = $event->postId;
                $first->meta_key = $key;
                $first->meta_value = $value == null ? '' : $value;
                $first->save();
            }
        }

        if ($event->submitType == 'create') {
            $uri = 'http://data.zz.baidu.com/urls?appid=1594785281517469&token=a5ssxQvomsrtEAC1&type=realtime';
            $links = 'https://loocode.com/post/' . $event->postId;
            $http = new Client();
            try {
                $response = $http->post($uri, [
                    'header' => [
                        'Content-Type' => 'text/plain',
                    ],
                    'body' => implode("\n", [$links])
                ]);
                info($response->getBody());
            } catch (\Throwable $e) {
                info($e->getTraceAsString());
            }
        }
    }

}
