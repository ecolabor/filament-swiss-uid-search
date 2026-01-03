<?php

use Ecolabor\FilamentSwissUidSearch\Forms\Components\UidSearch;
use Ecolabor\FilamentSwissUidSearch\Forms\Components\CompanySearch;

describe('UidSearch Component', function () {
    it('can be instantiated', function () {
        $component = UidSearch::make('uid');
        
        expect($component)->toBeInstanceOf(UidSearch::class);
        expect($component->getName())->toBe('uid');
    });

    it('can set field mappings', function () {
        $component = UidSearch::make('uid')
            ->mapName('company_name')
            ->mapStreet('street')
            ->mapCity('city');
        
        expect($component)->toBeInstanceOf(UidSearch::class);
    });

    it('has placeholder by default', function () {
        $component = UidSearch::make('uid');
        
        expect($component->getPlaceholder())->toBe('CHE-XXX.XXX.XXX');
    });
});

describe('CompanySearch Component', function () {
    it('can be instantiated', function () {
        $component = CompanySearch::make('company');
        
        expect($component)->toBeInstanceOf(CompanySearch::class);
        expect($component->getName())->toBe('company');
    });

    it('can set search limit', function () {
        $component = CompanySearch::make('company')
            ->searchLimit(25);
        
        expect($component)->toBeInstanceOf(CompanySearch::class);
    });

    it('can set field mappings', function () {
        $component = CompanySearch::make('company')
            ->mapUid('uid_field')
            ->mapName('name_field')
            ->mapStreet('street_field');
        
        expect($component)->toBeInstanceOf(CompanySearch::class);
    });
});
