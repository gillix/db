<?php

namespace glx\DB\Query\Composer\Extender;

trait Qualifier
{
    protected static function qualified(string $field, string $table = null): string
    {
        if ($table) {
            return "{$table}.{$field}";
        }
        return $field;
    }
}
