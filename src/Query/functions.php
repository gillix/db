<?php

namespace glx\DB\Query;

function raw(string $raw, ...$values): Raw
{
    return new Raw($raw, ...$values);
}


function cond($name, $operator = null, $value = null): I\ConditionExpression
{
    if ($name instanceof I\ConditionExpression) {
        return $name;
    }
    if (is_string($name) && $operator === null && $value === null) {
        return raw($name);
    }

    return new Condition($name, $operator, $value);
}


function _or_(...$entries): I\Sequence
{
    $seq = seq();
    foreach ($entries as $i => $entry) {
        if (is_array($entry)) {
            $entry = cond($entry);
        } elseif (!$entry instanceof I\ConditionExpression) {
            continue;
        }
        $seq->add($entry, 'or');
    }

    return $seq;
}

function _and_(...$entries): I\Sequence
{
    return seq(...$entries);
}

function seq(...$entries): I\Sequence
{
    return new Sequence(...$entries);
}
