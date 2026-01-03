<div class="space-y-4" x-data>
    @if($error)
    <div class="rounded-lg bg-danger-50 dark:bg-danger-500/10 p-4">
        <div class="flex items-start gap-3">
            <x-heroicon-s-exclamation-triangle class="h-5 w-5 text-danger-500 shrink-0" />
            <p class="text-sm text-danger-700 dark:text-danger-400">{{ $error }}</p>
        </div>
    </div>
    @elseif($results->isEmpty())
    <div class="text-center py-12">
        <x-heroicon-o-building-office-2 class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" />
        <h3 class="mt-4 text-sm font-semibold text-gray-900 dark:text-white">
            {{ __('filament-swiss-uid-search::messages.no_results_for', ['term' => $searchTerm]) }}
        </h3>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            {{ __('filament-swiss-uid-search::messages.try_different_search') }}
        </p>
    </div>
    @else
    <div class="divide-y divide-gray-100 dark:divide-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        @foreach($results as $company)
        @php
            $address = $company->address;
            $street = $address?->street ?? '';
            $houseNumber = $address?->houseNumber ?? '';
            $zipCode = $address?->swissZipCode ?? '';
            $city = $address?->town ?? '';
            $canton = $address?->cantonAbbreviation ?? '';
        @endphp
        <button
            type="button"
            x-on:click="
                $dispatch('close-modal', { id: '{{ $this->getId() }}.actions.searchCompany' });
                @if($fieldMappings['uid'] ?? false)
                $wire.$parent.set('{{ $fieldMappings['uid'] }}', '{{ $company->uidFormatted }}');
                @endif
                @if($fieldMappings['name'] ?? false)
                $wire.$parent.set('{{ $fieldMappings['name'] }}', '{{ addslashes($company->organisationName) }}');
                @endif
                @if($fieldMappings['legal_form'] ?? false)
                $wire.$parent.set('{{ $fieldMappings['legal_form'] }}', '{{ addslashes($company->legalFormText ?? '') }}');
                @endif
                @if($fieldMappings['street'] ?? false)
                $wire.$parent.set('{{ $fieldMappings['street'] }}', '{{ addslashes($street) }}');
                @endif
                @if($fieldMappings['house_number'] ?? false)
                $wire.$parent.set('{{ $fieldMappings['house_number'] }}', '{{ addslashes($houseNumber) }}');
                @endif
                @if($fieldMappings['zip_code'] ?? false)
                $wire.$parent.set('{{ $fieldMappings['zip_code'] }}', '{{ $zipCode }}');
                @endif
                @if($fieldMappings['city'] ?? false)
                $wire.$parent.set('{{ $fieldMappings['city'] }}', '{{ addslashes($city) }}');
                @endif
                @if($fieldMappings['canton'] ?? false)
                $wire.$parent.set('{{ $fieldMappings['canton'] }}', '{{ $canton }}');
                @endif
            "
            class="w-full text-left px-4 py-4 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors duration-150 focus:outline-none focus:bg-primary-50 dark:focus:bg-primary-900/20 group cursor-pointer"
        >
            <div class="flex items-start gap-4">
                {{-- Company Icon --}}
                <div class="shrink-0 mt-0.5">
                    <div class="h-10 w-10 rounded-lg bg-primary-100 dark:bg-primary-900/50 flex items-center justify-center group-hover:bg-primary-200 dark:group-hover:bg-primary-800/50 transition-colors">
                        <x-heroicon-s-building-office-2 class="h-5 w-5 text-primary-600 dark:text-primary-400" />
                    </div>
                </div>

                {{-- Company Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-primary-700 dark:group-hover:text-primary-300 transition-colors">
                                {{ $company->organisationName }}
                            </p>
                            @if($company->organisationAdditionalName)
                            <p class="text-sm text-gray-600 dark:text-gray-400 truncate">
                                {{ $company->organisationAdditionalName }}
                            </p>
                            @endif
                        </div>
                        @if($company->legalFormText)
                        <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                            {{ $company->legalFormText }}
                        </span>
                        @endif
                    </div>

                    <div class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1.5 text-xs text-gray-500 dark:text-gray-400">
                        {{-- UID --}}
                        <span class="inline-flex items-center gap-1.5">
                            <x-heroicon-m-identification class="h-4 w-4 text-gray-400" />
                            <span class="font-mono font-medium">{{ $company->uidFormatted }}</span>
                        </span>

                        {{-- Address --}}
                        @if($company->address)
                        <span class="inline-flex items-center gap-1.5">
                            <x-heroicon-m-map-pin class="h-4 w-4 text-gray-400" />
                            <span>{{ $company->address->getOneLiner() }}</span>
                        </span>
                        @endif
                    </div>
                </div>

                {{-- Select Indicator --}}
                <div class="shrink-0 self-center">
                    <div class="h-8 w-8 rounded-full bg-primary-100 dark:bg-primary-900/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <x-heroicon-m-check class="h-4 w-4 text-primary-600 dark:text-primary-400" />
                    </div>
                </div>
            </div>
        </button>
        @endforeach
    </div>

    <div class="flex items-center justify-between pt-2">
        <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
            <x-heroicon-m-check-circle class="h-4 w-4 text-success-500" />
            {{ __('filament-swiss-uid-search::messages.results_count', ['count' => $results->count()]) }}
        </p>
        <p class="text-xs text-gray-400 dark:text-gray-500 flex items-center gap-1.5">
            <x-heroicon-m-cursor-arrow-rays class="h-4 w-4" />
            {{ __('filament-swiss-uid-search::messages.click_to_select') }}
        </p>
    </div>
    @endif
</div>
