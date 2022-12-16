<?php

namespace glx\DB\Query;

class Aggregated extends Paginated implements I\Aggregated
{
    protected array $aggregated;

    public function __construct(
        array &$array,
        array $aggregated,
        $page = 1,
        $perPage = Paginated::DEFAULT_PER_PAGE,
    ) {
        $this->aggregated = $aggregated;
        parent::__construct($array, $aggregated['total'], $page, $perPage);
    }

    public function aggregated(string $field)
    {
        return $this->aggregated[$field];
    }
}
