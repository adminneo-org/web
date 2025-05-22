<?php

namespace Services;

use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

class RouterFactory
{
    public function createRouter(): RouteList
    {
        $router = new RouteList();

        $router[] = $this->createWebRouteList();

        return $router;
    }

    private function createWebRouteList(): RouteList
    {
        $routeList = new RouteList('Web');

        $routeList[] = new Route("files/v<version>/<options [^_]*_[^_]*_[^_]*>/<project admin|editor>neo-<version2>.php", "Download:file");
        $routeList[] = new Route("<presenter>/<action>", "Home:default");

        return $routeList;
    }
}
