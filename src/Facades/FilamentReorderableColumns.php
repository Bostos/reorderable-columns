<?php

namespace Bostos\FilamentReorderableColumns\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Bostos\FilamentReorderableColumns\FilamentReorderableColumns
 */
class FilamentReorderableColumns extends Facade
{
    protected static function getFacadeAccessor()
    {
        return FilamentReorderableColumns::class;
    }
}
