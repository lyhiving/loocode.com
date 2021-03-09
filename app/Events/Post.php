<?php
declare(strict_types=1);

namespace App\Events;


class Post
{
    public int $postId = 0;
    public array $relationIds = [];
    public array $meta = [];
    public string $submitType = 'create';
    public string $postsType = 'post';

    public function __construct(
        int $postId,
        array $relationIds,
        array $meta = [],
        string $postsType = 'post',
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
