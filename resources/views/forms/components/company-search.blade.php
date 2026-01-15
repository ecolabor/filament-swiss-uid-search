<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <x-filament::input.wrapper
        :prefix="$getPrefixLabel()"
        :prefix-icon="$getPrefixIcon()"
        :prefix-icon-color="$getPrefixIconColor()"
        :suffix="$getSuffixLabel()"
        :suffix-icon="$getSuffixIcon()"
        :suffix-icon-color="$getSuffixIconColor()"
        :valid="! $errors->has($getStatePath())"
    >
        <x-filament::input
            :attributes="
                $getExtraInputAttributeBag()
                    ->merge([
                        'id' => $getId(),
                        'disabled' => $isDisabled(),
                        'maxlength' => $getMaxLength(),
                        'minlength' => $getMinLength(),
                        'placeholder' => $getPlaceholder(),
                        'readonly' => $isReadOnly(),
                        'required' => $isRequired() && ! $isConcealed(),
                        'type' => 'text',
                        $applyStateBindingModifiers('wire:model') => $getStatePath(),
                        'x-on:keydown.enter.prevent' => '
                            $event.preventDefault();
                            $event.stopPropagation();
                            const actionButton = $el.closest(\'.fi-input-wrp\').querySelector(\'[data-action-name=\\\'searchCompany\\\']\') || $el.closest(\'.fi-fo-field\').querySelector(\'button\');
                            if (actionButton) actionButton.click();
                        ',
                    ], escape: false)
            "
        />

        <x-slot name="suffix">
            @if ($suffixAction = $getSuffixAction())
                {{ $suffixAction }}
            @endif
        </x-slot>
    </x-filament::input.wrapper>
</x-dynamic-component>
