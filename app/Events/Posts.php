<?php


namespace App\Events;


use App\Models\Post;

class Posts
{
    public int $postId = 0;
    public array $relationIds = [];
    public array $meta = [];
    public string $submitType = 'create';
    public string $postsType = Post::TYPE_POST;

    public function __construct(
        int $postId,
        array $relationIds,
        array $meta = [],
        string $postsType = Post::TYPE_POST,
        string $submitType = 'create'
    )
    {
        $this->postId = $postId;

        $this->relationIds = $relationIds;

        $this->meta = $meta;

        $this->postsType = $postsType;

        $this->submitType = $submitType;
    }

}
