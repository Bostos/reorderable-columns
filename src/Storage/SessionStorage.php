<?php

namespace Bostos\FilamentReorderableColumns\Storage;

class SessionStorage implements ColumnOrderStorage
{
    protected function getKey(string $tableId): string
    {
        return "table_order.{$tableId}";
    }

    public function get(string $tableId): ?array
    {
        return session($this->getKey($tableId));
    }

    public function set(string $tableId, array $order): void
    {
        session([$this->getKey($tableId) => $order]);
    }
}
