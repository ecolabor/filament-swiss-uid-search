<?php

declare(strict_types=1);

namespace Ecolabor\FilamentSwissUidSearch\Forms\Components;

use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Ecolabor\SwissUid\Data\UidEntity;
use Ecolabor\SwissUid\Facades\SwissUid;

class UidSearch extends TextInput
{
    protected string $view = 'filament-swiss-uid-search::forms.components.uid-search';

    protected ?Closure $onCompanySelected = null;

    protected array $fieldMappings = [];

    protected bool $autoFillAddress = true;

    protected bool $showValidationIndicator = true;

    protected function setUp(): void
    {
        parent::setUp();

        $this->placeholder('CHE-XXX.XXX.XXX');
        $this->maxLength(15);
        
        $this->suffixAction(
            Action::make('searchUid')
                ->icon('heroicon-o-magnifying-glass')
                ->tooltip(__('filament-swiss-uid-search::messages.search_uid'))
                ->action(function ($state, Set $set, $component) {
                    $this->performSearch($state, $set, $component);
                })
        );
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

    public function autoFillAddress(bool $autoFill = true): static
    {
        $this->autoFillAddress = $autoFill;

        return $this;
    }

    public function showValidationIndicator(bool $show = true): static
    {
        $this->showValidationIndicator = $show;

        return $this;
    }

    public function getShowValidationIndicator(): bool
    {
        return $this->showValidationIndicator;
    }

    public function getValidationStatus(): ?string
    {
        return null; // Handled by Alpine.js
    }

    /**
     * Validate UID format (called via wire from the view).
     */
    public function validateUidFormat(string $uid): bool
    {
        $normalized = preg_replace('/[^0-9]/', '', $uid);
        
        if (strlen($normalized) !== 9) {
            return false;
        }

        // Checksum validation (Modulo 11)
        $weights = [5, 4, 3, 2, 7, 6, 5, 4];
        $sum = 0;

        for ($i = 0; $i < 8; $i++) {
            $sum += (int) $normalized[$i] * $weights[$i];
        }

        $remainder = $sum % 11;
        $checkDigit = 11 - $remainder;

        if ($checkDigit === 10) {
            return false;
        }
        if ($checkDigit === 11) {
            $checkDigit = 0;
        }

        return (int) $normalized[8] === $checkDigit;
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
     * Map canton to a form field.
     */
    public function mapCanton(string $fieldName): static
    {
        $this->fieldMappings['canton'] = $fieldName;

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
     * Map VAT number to a form field.
     */
    public function mapVatNumber(string $fieldName): static
    {
        $this->fieldMappings['vat_number'] = $fieldName;

        return $this;
    }

    protected function performSearch(mixed $state, Set $set, $component): void
    {
        if (empty($state)) {
            $this->showNotification('error', __('filament-swiss-uid-search::messages.enter_uid'));
            return;
        }

        try {
            $entity = SwissUid::getByUid($state);

            if (! $entity) {
                $this->showNotification('warning', __('filament-swiss-uid-search::messages.uid_not_found'));
                return;
            }

            // Update the UID field with formatted value
            $set($this->getName(), $entity->uidFormatted);

            // Auto-fill mapped fields
            $this->fillMappedFields($entity, $set);

            // Execute custom callback
            if ($this->onCompanySelected) {
                $this->evaluate($this->onCompanySelected, [
                    'entity' => $entity,
                    'set' => $set,
                ]);
            }

            $this->showNotification('success', __('filament-swiss-uid-search::messages.company_found', [
                'name' => $entity->name,
            ]));

        } catch (\Exception $e) {
            $this->showNotification('error', __('filament-swiss-uid-search::messages.search_error'));
        }
    }

    protected function fillMappedFields(UidEntity $entity, Set $set): void
    {
        $mappings = [
            'name' => $entity->getFullName(),
            'street' => $entity->address?->street,
            'house_number' => $entity->address?->houseNumber,
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
    }

    protected function showNotification(string $type, string $message): void
    {
        match ($type) {
            'success' => \Filament\Notifications\Notification::make()
                ->title($message)
                ->success()
                ->send(),
            'warning' => \Filament\Notifications\Notification::make()
                ->title($message)
                ->warning()
                ->send(),
            'error' => \Filament\Notifications\Notification::make()
                ->title($message)
                ->danger()
                ->send(),
            default => null,
        };
    }
}
