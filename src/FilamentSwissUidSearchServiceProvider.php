<?php

declare(strict_types=1);

namespace Ecolabor\FilamentSwissUidSearch;

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Ecolabor\FilamentSwissUidSearch\Livewire\UidSearchModal;

class FilamentSwissUidSearchServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-swiss-uid-search';

    public static string $viewNamespace = 'filament-swiss-uid-search';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasViews(static::$viewNamespace)
            ->hasTranslations()
            ->hasConfigFile()
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('ecolabor/filament-swiss-uid-search');
            });
    }

    public function packageBooted(): void
    {
        // Register Livewire components
        Livewire::component('filament-swiss-uid-search::uid-search-modal', UidSearchModal::class);

        // Register assets if needed
        if (file_exists(__DIR__ . '/../resources/dist/filament-swiss-uid-search.css')) {
            FilamentAsset::register([
                Css::make('filament-swiss-uid-search', __DIR__ . '/../resources/dist/filament-swiss-uid-search.css'),
            ], 'ecolabor/filament-swiss-uid-search');
        }
    }
}
