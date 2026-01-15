<?php

declare(strict_types=1);

namespace Ecolabor\FilamentSwissUidSearch\Forms\Components;

use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Enums\Width;
use Ecolabor\SwissUid\Data\UidEntity;
use Ecolabor\SwissUid\Facades\SwissUid;

class CompanySearch extends TextInput
{
    protected string $view = 'filament-swiss-uid-search::forms.components.company-search';

    protected ?Closure $onCompanySelected = null;

    protected array $fieldMappings = [];

    protected int $searchLimit = 50;

    protected function setUp(): void
    {
        parent::setUp();

        $this->placeholder(__('filament-swiss-uid-search::messages.company_name_placeholder'));
        
        $this->suffixAction(
            Action::make('searchCompany')
                ->icon('heroicon-o-magnifying-glass')
                ->tooltip(__('filament-swiss-uid-search::messages.search_company'))
                ->modalHeading(__('filament-swiss-uid-search::messages.search_results'))
                ->modalWidth(Width::FourExtraLarge)
                ->modalContent(fn ($state) => $this->getSearchResultsView($state))
                ->modalSubmitAction(false)
                ->modalCancelActionLabel(__('filament-swiss-uid-search::messages.close'))
        );
    }

    public function getFieldMappings(): array
    {
        return $this->fieldMappings;
    }

    public function getSearchLimit(): int
    {
        return $this->searchLimit;
    }

    public function onCompanySelected(?Closure $callback): static
    {
        $this->onCompanySelected = $callback;

        return $this;
    }

    public function fieldMappings(array $mappings): static
    {
        $this->fieldMappings = $mappings;

        return $this;
    }

    public function searchLimit(int $limit): static
    {
        $this->searchLimit = $limit;

        return $this;
    }

    /**
     * Map UID to a form field.
     */
    public function mapUid(string $fieldName): static
    {
        $this->fieldMappings['uid'] = $fieldName;

        return $this;
    }

    /**
     * Map company name to a form field.
     */
    public function mapName(string $fieldName): static
    {
        $this->fieldMappings['name'] = $fieldName;

        return $this;
    }

    /**
     * Map street to a form field.
     */
    public function mapStreet(string $fieldName): static
    {
        $this->fieldMappings['street'] = $fieldName;

        return $this;
    }

    /**
     * Map house number to a form field.
     */
    public function mapHouseNumber(string $fieldName): static
    {
        $this->fieldMappings['house_number'] = $fieldName;

        return $this;
    }

    /**
     * Map zip code to a form field.
     */
    public function mapZipCode(string $fieldName): static
    {
        $this->fieldMappings['zip_code'] = $fieldName;

        return $this;
    }

    /**
     * Map city/town to a form field.
     */
    public function mapCity(string $fieldName): static
    {
        $this->fieldMappings['city'] = $fieldName;

        return $this;
    }

    /**
     * Map legal form to a form field.
     */
    public function mapLegalForm(string $fieldName): static
    {
        $this->fieldMappings['legal_form'] = $fieldName;

        return $this;
    }

    /**
     * Map canton to a form field.
     */
    public function mapCanton(string $fieldName): static
    {
        $this->fieldMappings['canton'] = $fieldName;

        return $this;
    }

    protected function getSearchResultsView(mixed $state): \Illuminate\Contracts\View\View
    {
        $results = collect();
        $error = null;

        if (! empty($state)) {
            try {
                $searchResult = SwissUid::searchByName($state, $this->searchLimit);
                $results = $searchResult->entities;
                
                if ($searchResult->hasError()) {
                    $error = $searchResult->errorMessage;
                }
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        return view('filament-swiss-uid-search::components.search-results', [
            'results' => $results,
            'searchTerm' => $state,
            'error' => $error,
            'fieldMappings' => $this->fieldMappings,
        ]);
    }
}
