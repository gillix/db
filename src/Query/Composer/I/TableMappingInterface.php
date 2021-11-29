<?php

namespace glx\DB\Query\Composer\I;

use glx\DB\Query\Composer\QueryComposer;

interface TableMappingInterface extends MappingElement
{
    public function alias(): ?string;

    public function join(QueryComposer $composer);
}
