<?php

declare(strict_types=1);

namespace WebModule\Tools;

class Helpers
{
    public function load(string $filter): ?callable
    {
        if (method_exists($this, $filter)) {
            return [$this, $filter];
        }

        return null;
    }

//    public static function format(string $value): string
//    {
//        return $value
//    }
}
