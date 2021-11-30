<?php

namespace glx\DB\Query\Composer\I;

interface TableMappingInterface extends MappingElement
{
    public function alias(): ?string;

    public function join();
}
