<?php

namespace Bostos\ReorderableColumns\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Bostos\ReorderableColumns\ReorderableColumns
 */
class ReorderableColumns extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ReorderableColumns::class;
    }
}
