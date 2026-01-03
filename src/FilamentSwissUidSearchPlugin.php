<?php

declare(strict_types=1);

namespace Ecolabor\FilamentSwissUidSearch;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;

class FilamentSwissUidSearchPlugin implements Plugin
{
    use EvaluatesClosures;

    protected string|Closure $defaultLanguage = 'de';

    protected bool|Closure $showVatNumber = true;

    protected bool|Closure $showLegalForm = true;

    protected bool|Closure $showAddress = true;

    protected int|Closure $searchLimit = 50;

    public function getId(): string
    {
        return 'filament-swiss-uid-search';
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

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function defaultLanguage(string|Closure $language): static
    {
        $this->defaultLanguage = $language;

        return $this;
    }

    public function getDefaultLanguage(): string
    {
        return $this->evaluate($this->defaultLanguage);
    }

    public function showVatNumber(bool|Closure $show = true): static
    {
        $this->showVatNumber = $show;

        return $this;
    }

    public function shouldShowVatNumber(): bool
    {
        return $this->evaluate($this->showVatNumber);
    }

    public function showLegalForm(bool|Closure $show = true): static
    {
        $this->showLegalForm = $show;

        return $this;
    }

    public function shouldShowLegalForm(): bool
    {
        return $this->evaluate($this->showLegalForm);
    }

    public function showAddress(bool|Closure $show = true): static
    {
        $this->showAddress = $show;

        return $this;
    }

    public function shouldShowAddress(): bool
    {
        return $this->evaluate($this->showAddress);
    }

    public function searchLimit(int|Closure $limit): static
    {
        $this->searchLimit = $limit;

        return $this;
    }

    public function getSearchLimit(): int
    {
        return $this->evaluate($this->searchLimit);
    }
}
