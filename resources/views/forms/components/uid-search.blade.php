<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div 
        x-data="{
            state: $wire.$entangle('{{ $getStatePath() }}'),
            isValidating: false,
            validationStatus: null,
            
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
    >
        <x-filament::input.wrapper
            :prefix="$getPrefixLabel()"
            :prefix-icon="$getPrefixIcon() ?? 'heroicon-o-identification'"
            :prefix-icon-color="$getPrefixIconColor()"
            :valid="! $errors->has($getStatePath())"
        >
            <x-filament::input
                type="text"
                x-model="state"
                x-on:blur="validateUid()"
                x-on:input.debounce.500ms="validateUid()"
                :placeholder="$getPlaceholder() ?? 'CHE-XXX.XXX.XXX'"
                maxlength="15"
                :disabled="$isDisabled()"
                :attributes="$attributes->merge($getExtraInputAttributes(), escape: false)"
                class="pr-24"
            />
            
            <x-slot name="suffix">
                <div class="flex items-center gap-1">
                    {{-- Validation Status Indicator --}}
                    <template x-if="isValidating">
                        <x-filament::loading-indicator class="h-5 w-5 text-gray-400" />
                    </template>
                    
                    <template x-if="!isValidating && validationStatus === 'valid'">
                        <x-heroicon-o-check-circle class="h-5 w-5 text-success-500" />
                    </template>
                    
                    <template x-if="!isValidating && validationStatus === 'invalid'">
                        <x-heroicon-o-x-circle class="h-5 w-5 text-danger-500" />
                    </template>
                    
                    <template x-if="!isValidating && validationStatus === 'error'">
                        <x-heroicon-o-exclamation-triangle class="h-5 w-5 text-warning-500" />
                    </template>
                    
                    {{-- Search Button --}}
                    {{ $getAction('searchUid') }}
                </div>
            </x-slot>
        </x-filament::input.wrapper>
    </div>
</x-dynamic-component>
