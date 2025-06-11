<?php

namespace Bostos\FilamentReorderableColumns\Storage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DatabaseStorage implements ColumnOrderStorage
{
    public function get(string $tableId): ?array
    {
        $model = $this->getModel();

        return $model->query()
            ->where('user_id', Auth::id())
            ->where('table_id', $tableId)
            ->value('order');
    }

    public function set(string $tableId, array $order): void
    {
        $model = $this->getModel();
        $model->query()->updateOrCreate(
            ['user_id' => Auth::id(), 'table_id' => $tableId],
            ['order' => $order]
        );
    }

    protected function getModel(): Model
    {
        return new class extends Model
        {
            protected $table = 'column_orders';

            protected $guarded = [];

            protected $casts = ['order' => 'array'];
        };
    }
}
