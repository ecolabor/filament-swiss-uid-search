<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div 
        x-data="{
            state: $wire.$entangle('{{ $getStatePath() }}'),
            isValidating: false,
            validationStatus: null, {{-- null, 'valid', 'invalid', 'error' --}}
            
            async validateUid() {
                if (!this.state || this.state.length < 9) {
                    this.validationStatus = null;
                    return;
                }
                
                this.isValidating = true;
                this.validationStatus = null;
                
                try {
                    const result = await $wire.call('validateUidFormat', this.state);
                    this.validationStatus = result ? 'valid' : 'invalid';
                } catch (e) {
                    this.validationStatus = 'error';
                } finally {
                    this.isValidating = false;
                }
            }
        }"
        class="relative"
    >
        {{-- Input Container --}}
        <div class="relative">
            {{-- Swiss Flag Prefix --}}
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">🇨🇭</span>
            </div>
            
            {{-- Input Field --}}
            <input
                type="text"
                x-model="state"
                x-on:blur="validateUid()"
                x-on:input.debounce.500ms="validateUid()"
                placeholder="{{ $getPlaceholder() ?? 'CHE-XXX.XXX.XXX' }}"
                maxlength="15"
                {{
                    $attributes
                        ->merge($getExtraInputAttributes(), escape: false)
                        ->class([
                            'block w-full rounded-lg border-gray-300 pl-10 pr-20',
                            'dark:border-gray-600 dark:bg-gray-700 dark:text-white',
                            'focus:border-primary-500 focus:ring-primary-500',
                            'shadow-sm transition duration-75',
                            'disabled:opacity-50 disabled:cursor-not-allowed',
                        ])
                }}
                @if ($isDisabled()) disabled @endif
                @if ($isReadOnly()) readonly @endif
            />
            
            {{-- Validation Status Indicator --}}
            <div class="absolute inset-y-0 right-12 flex items-center pr-2">
                {{-- Loading --}}
                <template x-if="isValidating">
                    <x-filament::loading-indicator class="h-5 w-5 text-gray-400" />
                </template>
                
                {{-- Valid --}}
                <template x-if="!isValidating && validationStatus === 'valid'">
                    <span class="flex items-center" title="{{ __('filament-swiss-uid-search::messages.uid_valid') }}">
                        <x-heroicon-o-check-circle class="h-5 w-5 text-success-500" />
                    </span>
                </template>
                
                {{-- Invalid --}}
                <template x-if="!isValidating && validationStatus === 'invalid'">
                    <span class="flex items-center" title="{{ __('filament-swiss-uid-search::messages.uid_invalid') }}">
                        <x-heroicon-o-x-circle class="h-5 w-5 text-danger-500" />
                    </span>
                </template>
                
                {{-- Error --}}
                <template x-if="!isValidating && validationStatus === 'error'">
                    <span class="flex items-center" title="{{ __('filament-swiss-uid-search::messages.validation_error') }}">
                        <x-heroicon-o-exclamation-triangle class="h-5 w-5 text-warning-500" />
                    </span>
                </template>
            </div>
            
            {{-- Search Button --}}
            <div class="absolute inset-y-0 right-0 flex items-center pr-1">
                {{ $getAction('searchUid') }}
            </div>
        </div>
        
        {{-- Helper Text --}}
        @if ($validationStatus = $getValidationStatus())
        <p class="mt-1 text-xs" 
            :class="{
                'text-success-600 dark:text-success-400': validationStatus === 'valid',
                'text-danger-600 dark:text-danger-400': validationStatus === 'invalid',
            }"
        >
            <template x-if="validationStatus === 'valid'">
                <span>{{ __('filament-swiss-uid-search::messages.uid_format_valid') }}</span>
            </template>
            <template x-if="validationStatus === 'invalid'">
                <span>{{ __('filament-swiss-uid-search::messages.uid_format_invalid') }}</span>
            </template>
        </p>
        @endif
    </div>
</x-dynamic-component>
