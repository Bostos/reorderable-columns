<?php

namespace Bostos\FilamentReorderableColumns;

use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentReorderableColumnsPlugin implements Plugin
{
    protected string $storageDriver = 'session';

    public function getId(): string
    {
        return 'reorderable-columns';
    }

    public function register(Panel $panel): void
    {
        //
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public function persistToSession(): static
    {
        $this->storageDriver = 'session';
        return $this;
    }

    public function persistToDatabase(): static
    {
        $this->storageDriver = 'database';
        return $this;
    }

    public function getStorageDriver(): string
    {
        return $this->storageDriver;
    }
}
