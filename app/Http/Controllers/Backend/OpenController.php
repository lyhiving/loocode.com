<?php
declare(strict_types=1);


namespace App\Http\Controllers\Backend;


use App\Attributes\Route;
use App\Http\Result;
use ReflectionClass;
use Symfony\Component\Finder\Finder;

/**
 * Class OpenController
 * @package App\Http\Controllers\Backend
 */
class OpenController extends BackendController
{
    /**
     * @return Result
     */
    public function configuration(): Result
    {
        return Result::ok([
            'timestamp' => time(),
        ]);
    }

    /**
     * @return Result
     * @throws \ReflectionException
     */
    public function menu(): Result
    {
        $routes = $this->getMenu();
        return Result::ok($routes);
    }

    /**
     * @return Route[]
     * @throws \ReflectionException
     */
    private function getMenu(): array
    {
        $files = Finder::create()->in([__DIR__])->name(['*.php'])->files();
        /**
         * @var $routes Route[]
         */
        $routes = $routeSorts = [];
        foreach ($files as $file) {
            if (!$file->isFile()) {
                continue;
            }
            $controller = __NAMESPACE__ . '\\' . $file->getBasename(".php");
            $ref = new ReflectionClass($controller);
            $attributes = $ref->getAttributes(Route::class);
            if (count($attributes) < 1) {
                continue;
            }
            $arguments = $attributes[0]->getArguments();
            if (empty($arguments['title'])) {
                continue;
            }
            /**
             * @var $route Route
             */
            if (isset($routes[$arguments['title']])) {
                $route = $routes[$arguments['title']];
                if (isset($arguments['sort']) && $route->sort != $arguments['sort']) {
                    $route->sort = $arguments['sort'];
                }
            } else {
                $route = $attributes[0]->newInstance();
            }
            $methods = $ref->getMethods();
            foreach ($methods as $method) {
                $attr = $method->getAttributes(Route::class);
                if (count($attr) < 1) {
                    continue;
                }
                $args = $attr[0]->getArguments();
                if (empty($args['title'])) {
                    continue;
                }
                /**
                 * @var $childRoute Route
                 */
                $childRoute = $attr[0]->newInstance();
                $route->appendChild($childRoute);
            }
            if (!isset($routes[$arguments['title']])) {
                $routes[$arguments['title']] = $route;
            }
        }
        foreach ($routes as $route) {
            $this->traverseChildren($route);
            $routeSorts[] = $route->sort;
        }
        array_multisort($routeSorts, SORT_ASC, $routes);
        return array_values($routes);
    }

    /**
     * @param Route $route
     */
    private function traverseChildren(Route $route)
    {
        $children = &$route->getChildren();
        foreach ($children as $name => $childRoute) {
            $parent = $childRoute->getParent();
            if (isset($children[$parent])) {
                $children[$parent]->appendChild($childRoute);
            }
            $routeSorts[] = $childRoute->sort;
        }
        if ($children) {
            array_multisort($routeSorts, SORT_ASC, $children);
        }
        foreach ($children as $name => $childRoute) {
            if (!empty($childRoute->getParent())) {
                unset($children[$name]);
            }
        }
    }
}
