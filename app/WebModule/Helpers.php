<?php

namespace WebModule;

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
