<?php

namespace glx\DB\Query\Composer;

use glx\DB\Query\Composer\I\TableMappingInterface;

trait TableMappingSingleton
{
    protected static TableMappingInterface $instance;

    public static function instance(...$args): TableMappingInterface
    {
        if (isset(self::$instance)) {
            return self::$instance;
        }
        $instance =  new static(...$args);
        if (!$instance instanceof TableMappingInterface) {
            throw new \Exception("Use this singleton trait in TableMappingInterface only");
        }
    }
}
