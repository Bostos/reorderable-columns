<?php

namespace BostosReorderableColumns\Concerns;

use Bostos\ReorderableColumns\Storage\ColumnOrderStorage;

trait HasReorderableColumns
{
    public function reorderTableColumns(string $tableId, array $newVisibleOrder): void
    {
        $storage = app(ColumnOrderStorage::class);

        $oldFullOrder = $storage->get($tableId);
        if ($oldFullOrder === null) {

            /** @var Table $table */
            $table = $this->getTable();
            $oldFullOrder = collect($table->getColumns())->map(fn ($column) => $column->getName())->toArray();
        }

        $newVisibleOrder = array_filter($newVisibleOrder, fn ($value) => is_string($value) && $value !== '');

        $visibleColumnsMap = array_flip($newVisibleOrder);

        $result = [];
        $visibleColumnsToInsert = $newVisibleOrder;

        foreach ($oldFullOrder as $columnName) {
            if (isset($visibleColumnsMap[$columnName])) {
                if (! empty($visibleColumnsToInsert)) {
                    array_push($result, ...$visibleColumnsToInsert);
                    $visibleColumnsToInsert = [];
                }
            } else {
                $result[] = $columnName;
            }
        }

        if (! empty($visibleColumnsToInsert)) {
            array_push($result, ...$visibleColumnsToInsert);
        }

        $storage->set($tableId, $result);
    }
}
