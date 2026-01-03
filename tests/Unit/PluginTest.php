<?php

use Ecolabor\FilamentSwissUidSearch\FilamentSwissUidSearchPlugin;

describe('FilamentSwissUidSearchPlugin', function () {
    it('can be instantiated', function () {
        $plugin = FilamentSwissUidSearchPlugin::make();
        
        expect($plugin)->toBeInstanceOf(FilamentSwissUidSearchPlugin::class);
    });

    it('has correct plugin ID', function () {
        $plugin = FilamentSwissUidSearchPlugin::make();
        
        expect($plugin->getId())->toBe('filament-swiss-uid-search');
    });

    it('can set default language', function () {
        $plugin = FilamentSwissUidSearchPlugin::make()
            ->defaultLanguage('fr');
        
        expect($plugin->getDefaultLanguage())->toBe('fr');
    });

    it('can set search limit', function () {
        $plugin = FilamentSwissUidSearchPlugin::make()
            ->searchLimit(25);
        
        expect($plugin->getSearchLimit())->toBe(25);
    });

    it('can toggle VAT number display', function () {
        $plugin = FilamentSwissUidSearchPlugin::make()
            ->showVatNumber(false);
        
        expect($plugin->shouldShowVatNumber())->toBeFalse();
    });

    it('can toggle legal form display', function () {
        $plugin = FilamentSwissUidSearchPlugin::make()
            ->showLegalForm(false);
        
        expect($plugin->shouldShowLegalForm())->toBeFalse();
    });

    it('can toggle address display', function () {
        $plugin = FilamentSwissUidSearchPlugin::make()
            ->showAddress(false);
        
        expect($plugin->shouldShowAddress())->toBeFalse();
    });

    it('has sensible defaults', function () {
        $plugin = FilamentSwissUidSearchPlugin::make();
        
        expect($plugin->getDefaultLanguage())->toBe('de');
        expect($plugin->getSearchLimit())->toBe(50);
        expect($plugin->shouldShowVatNumber())->toBeTrue();
        expect($plugin->shouldShowLegalForm())->toBeTrue();
        expect($plugin->shouldShowAddress())->toBeTrue();
    });
});
