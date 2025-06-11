<?php

namespace Bostos\FilamentReorderableColumns\Commands;

use Illuminate\Console\Command;

class FilamentReorderableColumnsCommand extends Command
{
    public $signature = 'filament-reorderable-columns';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
