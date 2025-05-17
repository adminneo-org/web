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

        $routeList[] = new Route("<presenter>/<action>", "Home:default");

        return $routeList;
    }
}
