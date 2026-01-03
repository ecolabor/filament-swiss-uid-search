<?php

describe('Translations', function () {
    it('loads German translations', function () {
        app()->setLocale('de');
        
        expect(__('filament-swiss-uid-search::messages.search'))->toBe('Suchen');
        expect(__('filament-swiss-uid-search::messages.company'))->toBe('Firma');
        expect(__('filament-swiss-uid-search::messages.uid'))->toBe('UID');
    });

    it('loads Swiss German translations', function () {
        app()->setLocale('de_CH');
        
        expect(__('filament-swiss-uid-search::messages.search'))->toBe('Suchen');
        expect(__('filament-swiss-uid-search::messages.close'))->toBe('Schliessen');
    });

    it('loads French translations', function () {
        app()->setLocale('fr');
        
        expect(__('filament-swiss-uid-search::messages.search'))->toBe('Rechercher');
        expect(__('filament-swiss-uid-search::messages.company'))->toBe('Entreprise');
    });

    it('loads Swiss French translations with IDE terminology', function () {
        app()->setLocale('fr_CH');
        
        expect(__('filament-swiss-uid-search::messages.uid'))->toBe('IDE');
        expect(__('filament-swiss-uid-search::messages.search_uid'))->toBe('Rechercher IDE');
    });

    it('loads Italian translations', function () {
        app()->setLocale('it');
        
        expect(__('filament-swiss-uid-search::messages.search'))->toBe('Cerca');
        expect(__('filament-swiss-uid-search::messages.company'))->toBe('Impresa');
    });

    it('loads Swiss Italian translations with IDI terminology', function () {
        app()->setLocale('it_CH');
        
        expect(__('filament-swiss-uid-search::messages.uid'))->toBe('IDI');
        expect(__('filament-swiss-uid-search::messages.search_uid'))->toBe('Cerca IDI');
    });

    it('loads English translations', function () {
        app()->setLocale('en');
        
        expect(__('filament-swiss-uid-search::messages.search'))->toBe('Search');
        expect(__('filament-swiss-uid-search::messages.company'))->toBe('Company');
    });
});
