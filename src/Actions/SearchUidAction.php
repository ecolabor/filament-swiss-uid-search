<?php

declare(strict_types=1);

namespace Ecolabor\FilamentSwissUidSearch\Actions;

use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Enums\MaxWidth;
use Ecolabor\SwissUid\Data\UidEntity;
use Ecolabor\SwissUid\Facades\SwissUid;

class SearchUidAction extends Action
{
    protected ?Closure $onCompanySelected = null;

    protected array $fieldMappings = [];

    protected int $searchLimit = 50;

    public static function getDefaultName(): ?string
    {
        return 'searchUid';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-swiss-uid-search::messages.search_company'));
        $this->icon('heroicon-o-building-office');
        $this->modalHeading(__('filament-swiss-uid-search::messages.search_company'));
        $this->modalWidth(MaxWidth::FourExtraLarge);
        $this->modalSubmitActionLabel(__('filament-swiss-uid-search::messages.search'));

        $this->form([
            TextInput::make('search_term')
                ->label(__('filament-swiss-uid-search::messages.search_term'))
                ->placeholder(__('filament-swiss-uid-search::messages.company_name_placeholder'))
                ->required(),
            
            Select::make('search_type')
                ->label(__('filament-swiss-uid-search::messages.search_type'))
                ->options([
                    'name' => __('filament-swiss-uid-search::messages.by_name'),
                    'uid' => __('filament-swiss-uid-search::messages.by_uid'),
                ])
                ->default('name'),

            TextInput::make('location')
                ->label(__('filament-swiss-uid-search::messages.location'))
                ->placeholder(__('filament-swiss-uid-search::messages.location_placeholder'))
                ->visible(fn (callable $get) => $get('search_type') === 'name'),

            Select::make('canton')
                ->label(__('filament-swiss-uid-search::messages.canton'))
                ->options($this->getCantonOptions())
                ->visible(fn (callable $get) => $get('search_type') === 'name'),
        ]);

        $this->action(function (array $data, Set $set) {
            $results = $this->performSearch($data);

            if ($results->isEmpty()) {
                $this->failureNotificationTitle(__('filament-swiss-uid-search::messages.no_results'));
                $this->failure();
                return;
            }

            // If only one result, auto-select it
            if ($results->count() === 1) {
                $this->applySelection($results->first(), $set);
                return;
            }

            // Multiple results - would need a modal to display them
            // For simplicity, we'll take the first result
            $this->applySelection($results->first(), $set);
        });
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

    public function mapUid(string $fieldName): static
    {
        $this->fieldMappings['uid'] = $fieldName;

        return $this;
    }

    public function mapName(string $fieldName): static
    {
        $this->fieldMappings['name'] = $fieldName;

        return $this;
    }

    public function mapStreet(string $fieldName): static
    {
        $this->fieldMappings['street'] = $fieldName;

        return $this;
    }

    public function mapZipCode(string $fieldName): static
    {
        $this->fieldMappings['zip_code'] = $fieldName;

        return $this;
    }

    public function mapCity(string $fieldName): static
    {
        $this->fieldMappings['city'] = $fieldName;

        return $this;
    }

    public function searchLimit(int $limit): static
    {
        $this->searchLimit = $limit;

        return $this;
    }

    protected function performSearch(array $data): \Illuminate\Support\Collection
    {
        $searchTerm = $data['search_term'] ?? '';
        $searchType = $data['search_type'] ?? 'name';

        if (empty($searchTerm)) {
            return collect();
        }

        try {
            if ($searchType === 'uid') {
                $entity = SwissUid::getByUid($searchTerm);
                return $entity ? collect([$entity]) : collect();
            }

            $criteria = [
                'organisationName' => $searchTerm,
                'maxNumberOfRecords' => $this->searchLimit,
            ];

            if (! empty($data['location'])) {
                $criteria['town'] = $data['location'];
            }

            if (! empty($data['canton'])) {
                $criteria['cantonAbbreviation'] = $data['canton'];
            }

            $result = SwissUid::search($criteria);

            return $result->entities;
        } catch (\Exception $e) {
            return collect();
        }
    }

    protected function applySelection(UidEntity $entity, Set $set): void
    {
        $mappings = [
            'uid' => $entity->uidFormatted,
            'name' => $entity->getFullName(),
            'street' => $entity->address?->getStreetLine(),
            'zip_code' => $entity->address?->swissZipCode,
            'city' => $entity->address?->town,
            'canton' => $entity->cantonAbbreviation,
            'legal_form' => $entity->legalForm,
            'vat_number' => $entity->vatNumber,
        ];

        foreach ($this->fieldMappings as $key => $fieldName) {
            if (isset($mappings[$key]) && $mappings[$key] !== null) {
                $set($fieldName, $mappings[$key]);
            }
        }

        if ($this->onCompanySelected) {
            $this->evaluate($this->onCompanySelected, [
                'entity' => $entity,
                'set' => $set,
            ]);
        }

        $this->successNotificationTitle(__('filament-swiss-uid-search::messages.company_found', [
            'name' => $entity->name,
        ]));
        $this->success();
    }

    protected function getCantonOptions(): array
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
