<?php

namespace glx\DB\Query\Composer;

trait Singleton
{
    protected static self $instance;

    public static function instance(...$args): self
    {
        return self::$instance ?? (self::$instance =  new static(...$args));
    }
}
