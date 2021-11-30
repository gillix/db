<?php

namespace glx\DB\Query;

class Result extends \ArrayObject implements I\Result
{

    public function array(): array
    {
        return $this->getArrayCopy();
    }

    // TODO: возможно пребразование значений в нужный тип "на лету" (как узнать нужный тип?)
}
