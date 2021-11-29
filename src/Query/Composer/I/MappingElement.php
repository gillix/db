<?php

namespace glx\DB\Query\Composer\I;

interface MappingElement
{
    public function depends(string $part, self $element): void;

    public function dependencies(): array;

    public function name(): string;

    public function __toString(): string;
}
