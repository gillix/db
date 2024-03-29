<?php

namespace glx\DB\Query;

use glx\DB\E\ConnectionFailed;
use glx\DB\E\QueryPerformingFailed;

class Update extends Joinable implements I\Update
{
    use Query;

    public function set($name, $value = null): I\Update
    {
        if (is_array($name)) {
            foreach ($name as $key => $val) {
                $this->set($key, $val);
            }

            return $this;
        }
        $this->units['fields'][$name] = $value;

        return $this;
    }

    public function table($table, string $alias = null): I\Update
    {
        return parent::table($table, $alias);
    }

    /**
     * @throws QueryPerformingFailed
     * @throws ConnectionFailed
     */
    public function perform(): int
    {
        [$sql, $values] = $this->compile();

        return $this->connection->execute($sql, $values);
    }

    public function compile(): array
    {
        return $this->compiler->update($this->units);
    }
}
