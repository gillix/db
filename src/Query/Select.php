<?php

namespace glx\DB\Query;

use Closure;
//use glx\Common;
use PDO;
use PDOStatement;

class Select extends Joinable implements I\Select
{
    use Query;

    public function get($callback = null): I\Result
    {
        // We need to select a specified column
        if (is_string($callback)) {
            $new = self::createFrom($this);
            $new->units['columns'] = [$callback];

            return $new->column();
        }
        // Select only specified columns
        if (is_array($callback)) {
            $new = self::createFrom($this);
            $new->units['columns'] = $callback;

            return $new->get();
        }
//        $stopwatch = Common\Stopwatch::start();

        return new Result($this->fetch($callback));
    }

    public function column($column = null): I\Result
    {
        if ($column && is_string($column)) {
            return $this->get($column);
        }

        return $this->get(static function (PDOStatement $stmt) use ($column) {
            return $stmt->fetchAll(PDO::FETCH_COLUMN, is_int($column) ? $column : 0);
        });
    }

    protected function fetch($callback = null)
    {
        [$sql, $values] = $this->compile();

//      if($callback instanceof \Closure)
        $result = $this->connection->perform(function ($query, $values) use ($callback) {
            $stmt = $this->connection->prepare($query);
            if ($values) {
                $this->connection::bind($stmt, $values);
            }
            $stmt->execute();
            if ($callback instanceof Closure) {
                return $callback($stmt);
            }
            $fetch = (array)$callback;
            $fetch[0] ??= PDO::FETCH_ASSOC;

            return $stmt->fetchAll(...$fetch);
        }, $sql, $values);
//      else
//        $result = $this->connection->query($sql, $values, is_int($callback) ? $callback : NULL);
        return $result;
    }

    public function compile(): array
    {
        return $this->compiler->select($this->units);
    }

    public function page($page, $pp = Paginated::DEFAULT_PER_PAGE, $callback = null): I\Paginated
    {
        // TODO: move aggregate functions to the separate unit for wide db compatibility
        $countable = $this->without(['columns', 'order', 'limit', 'offset']);
        if ($countable->units['group']) {
            $countable = $countable->new()->from($countable->select('COUNT(*)'), 'c');
        }
        $total = $countable->value('COUNT(*)');
        $result = $this->offset(($page - 1) * $pp)->limit($pp)->fetch($callback);

        return new Paginated($result, $total, $page, $pp);
    }

    public function from($table, string $alias = null): I\Select
    {
        return $this->table($table, $alias);
    }

    public function select(...$columns): I\Select
    {
        $this->units['columns'] = $this->units['columns'] ? array_merge($this->units['columns'], $columns) : $columns;

        return $this;
    }

    public function value($column = null)
    {
        if ($column) {
            $new = self::createFrom($this);
            $new->units['columns'][] = $column;
        }
        $new ??= $this;

        return $new->limit(1)->fetch(static function (PDOStatement $stmt) {
            return $stmt->fetchColumn();
        });
    }

    public function aggregated(array $columns, $page, $pp = null, $callback = null): I\Aggregated
    {
        foreach ($columns as $name => $function) {
            $columns[$name] = "$function($name) AS $name";
        }
        $columns['total'] = 'COUNT(*) AS total';
        $aggregates = $this->new()->from($this->without(['order', 'limit', 'offset']), 'a')->select($columns)->one();
        $result = $this->offset(($page - 1) * $pp)->limit($pp)->fetch($callback);

        return new Aggregated($result, $aggregates->array(), $page, $pp);
    }

    public function one(): I\Result
    {
        $result = $this->limit(1)->fetch(fn(PDOStatement $stmt) => $stmt->fetch(PDO::FETCH_ASSOC) ?: []);

        return new Result($result);
    }

    public function object($class = null, $args = null)
    {
        return $this->limit(1)->fetch(static function (PDOStatement $stmt) use ($class, $args) {
            return $stmt->fetchObject(...array_filter([$class ?? 'stdClass', $args]));
        });
    }

    public function having($name, $operator = null, $value = null): I\WhereClause
    {
        $expr = Condition::fetch($name, $operator, $value);
        if (!isset($this->units['having'])) {
            $this->units['having'] = $expr instanceof I\Sequence ? $expr : seq($expr);
        } else {
            $this->units['having']->add($expr);
        }

        return new WhereClause($this, $this->units['having']);
    }

    public function group(...$columns): I\Select
    {
        $this->units['group'] = $this->units['group'] ? array_merge($this->units['group'], $columns) : $columns;

        return $this;
    }
}
