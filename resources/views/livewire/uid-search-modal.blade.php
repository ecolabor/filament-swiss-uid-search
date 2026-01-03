<div class="space-y-4">
    {{-- Search Form --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                {{ __('filament-swiss-uid-search::messages.search_term') }}
            </label>
            <input 
                type="text" 
                wire:model="searchTerm"
                wire:keydown.enter="search"
                placeholder="{{ $searchType === 'uid' ? 'CHE-XXX.XXX.XXX' : __('filament-swiss-uid-search::messages.company_name_placeholder') }}"
                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
            />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                {{ __('filament-swiss-uid-search::messages.search_type') }}
            </label>
            <select 
                wire:model.live="searchType"
                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
            >
                <option value="name">{{ __('filament-swiss-uid-search::messages.by_name') }}</option>
                <option value="uid">{{ __('filament-swiss-uid-search::messages.by_uid') }}</option>
            </select>
        </div>

        <div class="flex items-end">
            <button 
                type="button"
                wire:click="search"
                wire:loading.attr="disabled"
                class="w-full inline-flex items-center justify-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-semibold text-white hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed transition"
            >
                <span wire:loading.remove wire:target="search">
                    <x-heroicon-o-magnifying-glass class="w-5 h-5 mr-2" />
                    {{ __('filament-swiss-uid-search::messages.search') }}
                </span>
                <span wire:loading wire:target="search">
                    <x-filament::loading-indicator class="w-5 h-5 mr-2" />
                    {{ __('filament-swiss-uid-search::messages.searching') }}
                </span>
            </button>
        </div>
    </div>

    {{-- Additional Filters (only for name search) --}}
    @if($searchType === 'name')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                {{ __('filament-swiss-uid-search::messages.location') }}
            </label>
            <input 
                type="text" 
                wire:model="location"
                placeholder="{{ __('filament-swiss-uid-search::messages.location_placeholder') }}"
                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
            />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                {{ __('filament-swiss-uid-search::messages.canton') }}
            </label>
            <select 
                wire:model="canton"
                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
            >
                @foreach($this->getCantons() as $code => $name)
                    <option value="{{ $code }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    @endif

    {{-- Error Message --}}
    @if($error)
    <div class="rounded-lg bg-danger-50 dark:bg-danger-500/10 p-4">
        <div class="flex">
            <x-heroicon-o-exclamation-circle class="w-5 h-5 text-danger-400" />
            <div class="ml-3">
                <p class="text-sm text-danger-700 dark:text-danger-400">{{ $error }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Results --}}
    @if(count($results) > 0)
    <div class="border dark:border-gray-700 rounded-lg overflow-hidden">
        <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        {{ __('filament-swiss-uid-search::messages.company') }}
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        {{ __('filament-swiss-uid-search::messages.uid') }}
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        {{ __('filament-swiss-uid-search::messages.address') }}
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        {{ __('filament-swiss-uid-search::messages.legal_form') }}
                    </th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        {{ __('filament-swiss-uid-search::messages.action') }}
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($results as $index => $company)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <td class="px-4 py-3">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $company['name'] }}
                        </div>
                        @if($company['additional_name'])
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $company['additional_name'] }}
                        </div>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white font-mono">
                        {{ $company['uid_formatted'] }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                        @if($company['address'])
                            {{ $company['address']['street'] }} {{ $company['address']['house_number'] }}<br>
                            {{ $company['address']['zip_code'] }} {{ $company['address']['town'] }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                        {{ $company['legal_form'] ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-right">
                        <button 
                            type="button"
                            wire:click="selectCompany({{ $index }})"
                            class="inline-flex items-center px-3 py-1.5 bg-primary-600 border border-transparent rounded-md text-xs font-semibold text-white hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition"
                        >
                            <x-heroicon-o-check class="w-4 h-4 mr-1" />
                            {{ __('filament-swiss-uid-search::messages.select') }}
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <p class="text-sm text-gray-500 dark:text-gray-400">
        {{ __('filament-swiss-uid-search::messages.results_count', ['count' => count($results)]) }}
    </p>
    @elseif(!$error && $searchTerm)
    <div class="text-center py-8">
        <x-heroicon-o-building-office class="mx-auto h-12 w-12 text-gray-400" />
        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
            {{ __('filament-swiss-uid-search::messages.no_results') }}
        </h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            {{ __('filament-swiss-uid-search::messages.try_different_search') }}
        </p>
    </div>
    @endif
</div>
