<?php

namespace Ecolabor\FilamentSwissUidSearch\Tests;

use Ecolabor\FilamentSwissUidSearch\FilamentSwissUidSearchServiceProvider;
use Ecolabor\SwissUid\SwissUidServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Support\SupportServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            SupportServiceProvider::class,
            FormsServiceProvider::class,
            SwissUidServiceProvider::class,
            FilamentSwissUidSearchServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
        config()->set('swiss-uid.environment', 'test');
        config()->set('swiss-uid.cache.enabled', false);
    }
}
