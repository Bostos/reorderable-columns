<?php

namespace Bostos\ReorderableColumns\Commands;

use Illuminate\Console\Command;

class ReorderableColumnsCommand extends Command
{
    public $signature = 'reorderable-columns';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
