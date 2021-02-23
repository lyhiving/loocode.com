<?php
declare(strict_types=1);

namespace App\Attributes;

use Attribute;

/**
 *
 * @since 8.0
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class Route implements \JsonSerializable
{
    public string $title;
    public string $parent;
    public int $sort;
    public string $icon;
    public string $link;
    public bool $hidden = false;
    public bool $home = false;

    public array $children;

    /**
     * Route constructor.
     * @param string $title
     * @param string $parent
     * @param int $sort
     * @param string $icon
     * @param string $link
     * @param bool $hidden
     */
    public function __construct(
        string $title,
        string $parent = '',
        int $sort = 0,
        string $icon = '',
        string $link = '',
        bool $hidden = false,
    )
    {
        $this->title = $title;
        $this->parent = $parent;
        $this->sort = $sort;
        $this->icon = $icon;
        $this->link = $link;
        $this->hidden = $hidden;
        $this->children = [];
    }

    /**
     * @param Route $route
     */
    public function appendChild(Route $route)
    {
        $this->children[$route->getTitle()] = $route;
    }

    /**
     * @return array
     */
    public function &getChildren(): array
    {
        return $this->children;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getParent(): string
    {
        return $this->parent;
    }

    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
        return [
            'title' => $this->title,
            'icon'  => $this->icon,
            'link'  => $this->link,
            'home'  => $this->home,
            'hidden' => $this->hidden,
            'children' => array_values($this->children),
        ];
    }
}
