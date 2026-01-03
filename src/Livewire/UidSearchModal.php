<?php

declare(strict_types=1);

namespace Ecolabor\FilamentSwissUidSearch\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Ecolabor\SwissUid\Data\UidEntity;
use Ecolabor\SwissUid\Facades\SwissUid;

class UidSearchModal extends Component
{
    public string $searchTerm = '';

    public string $searchType = 'name';

    public string $location = '';

    public string $canton = '';

    public array $results = [];

    public bool $isLoading = false;

    public ?string $error = null;

    public int $limit = 50;

    public array $fieldMappings = [];

    public function search(): void
    {
        $this->isLoading = true;
        $this->error = null;
        $this->results = [];

        if (empty($this->searchTerm)) {
            $this->error = __('filament-swiss-uid-search::messages.enter_search_term');
            $this->isLoading = false;
            return;
        }

        try {
            if ($this->searchType === 'uid') {
                $entity = SwissUid::getByUid($this->searchTerm);
                $this->results = $entity ? [$entity->toArray()] : [];
            } else {
                $criteria = [
                    'organisationName' => $this->searchTerm,
                    'maxNumberOfRecords' => $this->limit,
                ];

                if (! empty($this->location)) {
                    $criteria['town'] = $this->location;
                }

                if (! empty($this->canton)) {
                    $criteria['cantonAbbreviation'] = $this->canton;
                }

                $searchResult = SwissUid::search($criteria);

                if ($searchResult->hasError()) {
                    $this->error = $searchResult->errorMessage;
                } else {
                    $this->results = $searchResult->entities->map(fn (UidEntity $e) => $e->toArray())->toArray();
                }
            }
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }

        $this->isLoading = false;
    }

    #[On('select-company')]
    public function selectCompany(int $index): void
    {
        if (! isset($this->results[$index])) {
            return;
        }

        $company = $this->results[$index];

        $this->dispatch('company-selected', company: $company, mappings: $this->fieldMappings);
    }

    public function render(): View
    {
        return view('filament-swiss-uid-search::livewire.uid-search-modal');
    }

    public function getCantons(): array
    {
        return [
            '' => __('filament-swiss-uid-search::messages.all_cantons'),
            'AG' => 'Aargau',
            'AI' => 'Appenzell Innerrhoden',
            'AR' => 'Appenzell Ausserrhoden',
            'BE' => 'Bern',
            'BL' => 'Basel-Landschaft',
            'BS' => 'Basel-Stadt',
            'FR' => 'Freiburg',
            'GE' => 'Genf',
            'GL' => 'Glarus',
            'GR' => 'Graubünden',
            'JU' => 'Jura',
            'LU' => 'Luzern',
            'NE' => 'Neuenburg',
            'NW' => 'Nidwalden',
            'OW' => 'Obwalden',
            'SG' => 'St. Gallen',
            'SH' => 'Schaffhausen',
            'SO' => 'Solothurn',
            'SZ' => 'Schwyz',
            'TG' => 'Thurgau',
            'TI' => 'Tessin',
            'UR' => 'Uri',
            'VD' => 'Waadt',
            'VS' => 'Wallis',
            'ZG' => 'Zug',
            'ZH' => 'Zürich',
        ];
    }
}
