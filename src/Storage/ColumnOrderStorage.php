<?php

namespace Bostos\FilamentReorderableColumns\Storage;

interface ColumnOrderStorage
{
    public function get(string $tableId): ?array;

    public function set(string $tableId, array $order): void;
}
