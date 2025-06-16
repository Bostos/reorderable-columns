<?php

namespace Bostos\ReorderableColumns;

use Bostos\ReorderableColumns\Commands\ReorderableColumnsCommand;
use Bostos\ReorderableColumns\Storage\ColumnOrderStorage;
use Bostos\ReorderableColumns\Storage\DatabaseStorage;
use Bostos\ReorderableColumns\Storage\SessionStorage;
use Bostos\ReorderableColumns\Testing\TestsReorderableColumns;
use Filament\Facades\Filament;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables\Table;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ReorderableColumnsServiceProvider extends PackageServiceProvider
{
    public static string $name = 'reorderable-columns';

    public static string $viewNamespace = 'reorderable-columns';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('bostos/reorderable-columns');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void
    {
        parent::packageRegistered();

        $this->app->singleton(ColumnOrderStorage::class, function (Application $app) {

            /** @var ReorderableColumnsPlugin $plugin */
            $plugin = Filament::getCurrentPanel()->getPlugin('reorderable-columns');

            $driver = $plugin->getStorageDriver();

            return match ($driver) {
                'session' => new SessionStorage,
                'database' => new DatabaseStorage,
                default => new SessionStorage
            };
        });
    }

    public function packageBooted(): void
    {
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        FilamentIcon::register($this->getIcons());

        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/reorderable-columns/{$file->getFilename()}"),
                ], 'reorderable-columns-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsReorderableColumns);

        Table::macro('reorderableColumns', function (string $tableId) {

            /** @var Table $this */
            $storage = app(ColumnOrderStorage::class);
            $storedOrder = $storage->get($tableId);

            if ($storedOrder === null) {
                return $this;
            }

            $allColumns = $this->getColumns();
            $columnsByName = collect($allColumns)->keyBy(fn ($column) => $column->getName());
            $sortedColumns = [];

            foreach ($storedOrder as $columnName) {
                if ($columnsByName->has($columnName)) {
                    $sortedColumns[] = $columnsByName->pull($columnName);
                }
            }

            foreach ($columnsByName as $remainingColumn) {
                $sortedColumns[] = $remainingColumn;
            }

            return $this->columns($sortedColumns);
        });
    }

    protected function getAssetPackageName(): ?string
    {
        return 'bostos/reorderable-columns';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            Css::make('reorderable-columns-styles', __DIR__ . '/../resources/dist/reorderable-columns.css'),
            Js::make('reorderable-columns-scripts', __DIR__ . '/../resources/dist/reorderable-columns.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            ReorderableColumnsCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_column_orders_table',
        ];
    }
}
