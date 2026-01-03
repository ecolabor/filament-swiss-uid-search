@props([
    'company',
    'showVatNumber' => true,
    'showLegalForm' => true,
    'showAddress' => true,
    'showStatus' => true,
    'selectable' => false,
    'selected' => false,
])

@php
    $isActive = $company->isActive ?? ($company['is_active'] ?? true);
    $name = $company->organisationName ?? ($company['name'] ?? $company['organisation_name'] ?? '');
    $additionalName = $company->organisationAdditionalName ?? ($company['additional_name'] ?? null);
    $uidFormatted = $company->uidFormatted ?? ($company['uid_formatted'] ?? '');
    $vatFormatted = $company->vatNumberFormatted ?? ($company['vat_number_formatted'] ?? null);
    $legalForm = $company->legalFormText ?? ($company['legal_form'] ?? null);
    $address = $company->address ?? ($company['address'] ?? null);
    $canton = $company->cantonAbbreviation ?? ($company['canton'] ?? null);
@endphp

<div 
    @class([
        'relative rounded-lg border p-4 transition-all duration-200',
        'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800' => !$selected,
        'border-primary-500 dark:border-primary-400 bg-primary-50 dark:bg-primary-900/20 ring-2 ring-primary-500' => $selected,
        'hover:border-primary-300 dark:hover:border-primary-600 hover:shadow-md cursor-pointer' => $selectable,
    ])
    {{ $attributes }}
>
    {{-- Status Badge --}}
    @if($showStatus)
    <div class="absolute top-3 right-3">
        @if($isActive)
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-800 dark:bg-success-900/30 dark:text-success-400">
                <span class="w-1.5 h-1.5 mr-1 rounded-full bg-success-500"></span>
                {{ __('filament-swiss-uid-search::messages.status_active') }}
            </span>
        @else
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                <span class="w-1.5 h-1.5 mr-1 rounded-full bg-gray-400"></span>
                {{ __('filament-swiss-uid-search::messages.status_inactive') }}
            </span>
        @endif
    </div>
    @endif

    {{-- Company Header --}}
    <div class="flex items-start space-x-3">
        {{-- Icon --}}
        <div class="flex-shrink-0">
            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700">
                <x-heroicon-o-building-office-2 class="w-6 h-6 text-gray-500 dark:text-gray-400" />
            </div>
        </div>

        {{-- Company Info --}}
        <div class="flex-1 min-w-0 pr-16">
            {{-- Name --}}
            <h3 class="text-base font-semibold text-gray-900 dark:text-white truncate">
                {{ $name }}
            </h3>
            
            @if($additionalName)
            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                {{ $additionalName }}
            </p>
            @endif

            {{-- UID Badge --}}
            <div class="mt-2 flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-mono font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                    🇨🇭 {{ $uidFormatted }}
                </span>
                
                @if($showLegalForm && $legalForm)
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                    {{ $legalForm }}
                </span>
                @endif

                @if($canton)
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-50 text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                    {{ $canton }}
                </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Details --}}
    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
        {{-- Address --}}
        @if($showAddress && $address)
        <div class="flex items-start space-x-2">
            <x-heroicon-o-map-pin class="w-4 h-4 mt-0.5 text-gray-400 flex-shrink-0" />
            <div class="text-gray-600 dark:text-gray-300">
                @if(is_object($address))
                    @if($address->street || $address->houseNumber)
                        <div>{{ trim(($address->street ?? '') . ' ' . ($address->houseNumber ?? '')) }}</div>
                    @endif
                    @if($address->swissZipCode || $address->town)
                        <div>{{ trim(($address->swissZipCode ?? '') . ' ' . ($address->town ?? '')) }}</div>
                    @endif
                @elseif(is_array($address))
                    @if(isset($address['street']) || isset($address['house_number']))
                        <div>{{ trim(($address['street'] ?? '') . ' ' . ($address['house_number'] ?? '')) }}</div>
                    @endif
                    @if(isset($address['zip_code']) || isset($address['town']))
                        <div>{{ trim(($address['zip_code'] ?? '') . ' ' . ($address['town'] ?? '')) }}</div>
                    @endif
                @endif
            </div>
        </div>
        @endif

        {{-- VAT Number --}}
        @if($showVatNumber && $vatFormatted)
        <div class="flex items-center space-x-2">
            <x-heroicon-o-document-text class="w-4 h-4 text-gray-400 flex-shrink-0" />
            <span class="text-gray-600 dark:text-gray-300 font-mono text-xs">
                {{ $vatFormatted }}
            </span>
        </div>
        @endif
    </div>

    {{-- Selection Indicator --}}
    @if($selectable)
    <div class="absolute bottom-3 right-3">
        @if($selected)
            <x-heroicon-s-check-circle class="w-6 h-6 text-primary-500" />
        @else
            <x-heroicon-o-plus-circle class="w-6 h-6 text-gray-300 dark:text-gray-600" />
        @endif
    </div>
    @endif
</div>
