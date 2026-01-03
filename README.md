# Filament Swiss UID Search

[![Tests](https://img.shields.io/github/actions/workflow/status/ecolabor/filament-swiss-uid-search/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/ecolabor/filament-swiss-uid-search/actions/workflows/tests.yml)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/ecolabor/filament-swiss-uid-search.svg?style=flat-square)](https://packagist.org/packages/ecolabor/filament-swiss-uid-search)
[![Total Downloads](https://img.shields.io/packagist/dt/ecolabor/filament-swiss-uid-search.svg?style=flat-square)](https://packagist.org/packages/ecolabor/filament-swiss-uid-search)
[![PHP Version](https://img.shields.io/packagist/php-v/ecolabor/filament-swiss-uid-search.svg?style=flat-square)](https://packagist.org/packages/ecolabor/filament-swiss-uid-search)
[![License](https://img.shields.io/packagist/l/ecolabor/filament-swiss-uid-search.svg?style=flat-square)](https://packagist.org/packages/ecolabor/filament-swiss-uid-search)

Ein Filament v4 Plugin zur Suche nach Schweizer Unternehmen über den UID-Webservice (Unternehmens-Identifikationsnummer) des Bundesamts für Statistik.

## Features

- 🔍 **UID-Suchfeld** mit automatischer Firmenabfrage und Live-Validierung
- 🏢 **Firmensuche** nach Name mit Filteroptionen (Kanton, Ort)
- 🔄 **Automatisches Befüllen** von Formularfeldern
- ✅ **Live-Validierung** der UID mit visuellem Feedback (✓/✗)
- 🎴 **Company Card** Komponente für schöne Firmendarstellung
- 🇨🇭 **Schweizer Lokalisierung** (de_CH, fr_CH, it_CH) + Fallbacks (de, fr, it, en)
- 🎨 **Native Filament v4 Integration** - passt sich automatisch an dein Theme an
- 🌓 **Dark Mode** Support

## Installation

```bash
composer require ecolabor/filament-swiss-uid-search
```

### Plugin registrieren

Fügen Sie das Plugin zu Ihrem Panel hinzu:

```php
use Ecolabor\FilamentSwissUidSearch\FilamentSwissUidSearchPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugin(
            FilamentSwissUidSearchPlugin::make()
                ->defaultLanguage('de')
                ->searchLimit(50)
        );
}
```

## Verwendung

### UID-Suchfeld

Das `UidSearch`-Feld ermöglicht die direkte Suche nach einer UID-Nummer mit **Live-Validierung**:

```php
use Ecolabor\FilamentSwissUidSearch\Forms\Components\UidSearch;

public static function form(Form $form): Form
{
    return $form
        ->schema([
            UidSearch::make('uid')
                ->label('UID-Nummer')
                ->showValidationIndicator()  // Zeigt ✓ oder ✗ während der Eingabe
                ->mapName('company_name')
                ->mapStreet('street')
                ->mapZipCode('zip_code')
                ->mapCity('city')
                ->mapCanton('canton')
                ->mapLegalForm('legal_form'),

            TextInput::make('company_name')
                ->label('Firmenname'),

            TextInput::make('street')
                ->label('Strasse'),

            TextInput::make('zip_code')
                ->label('PLZ'),

            TextInput::make('city')
                ->label('Ort'),

            TextInput::make('canton')
                ->label('Kanton'),

            TextInput::make('legal_form')
                ->label('Rechtsform'),
        ]);
}
```

**Features des UidSearch-Feldes:**
- 🇨🇭 Schweizer Flagge als Prefix
- ✅ Live-Validierung mit Prüfziffer-Check
- 🔍 Such-Button zum Abrufen der Firmendaten
- 🔄 Automatisches Befüllen der verknüpften Felder

### Firmensuche nach Name

Das `CompanySearch`-Feld öffnet ein Modal zur Firmensuche:

```php
use Ecolabor\FilamentSwissUidSearch\Forms\Components\CompanySearch;

CompanySearch::make('company_search')
    ->label('Firma suchen')
    ->mapUid('uid')
    ->mapName('company_name')
    ->mapStreet('street')
    ->mapZipCode('zip_code')
    ->mapCity('city')
    ->searchLimit(100)
```

### Search Action

Alternativ können Sie eine Action verwenden:

```php
use Ecolabor\FilamentSwissUidSearch\Actions\SearchUidAction;

public static function form(Form $form): Form
{
    return $form
        ->schema([
            // ... Ihre Felder
        ])
        ->headerActions([
            SearchUidAction::make()
                ->mapUid('uid')
                ->mapName('company_name')
                ->mapStreet('street')
                ->mapZipCode('zip_code')
                ->mapCity('city'),
        ]);
}
```

### Custom Callback

Sie können auch einen eigenen Callback definieren:

```php
UidSearch::make('uid')
    ->onCompanySelected(function (UidEntity $entity, Set $set) {
        // Ihre eigene Logik hier
        $set('custom_field', $entity->vatNumber);
        
        // Log oder andere Aktionen
        logger()->info('Firma ausgewählt', ['uid' => $entity->uid]);
    })
```

### Company Card Komponente

Die `company-card` Blade-Komponente ermöglicht eine schöne Darstellung von Unternehmensdaten:

```blade
<x-filament-swiss-uid-search::components.company-card 
    :company="$company"
    :showVatNumber="true"
    :showLegalForm="true"
    :showAddress="true"
    :showStatus="true"
    :selectable="true"
    :selected="false"
/>
```

**Props:**

| Prop | Typ | Default | Beschreibung |
|------|-----|---------|--------------|
| `company` | `UidEntity\|array` | required | Das Unternehmensobjekt |
| `showVatNumber` | `bool` | `true` | MWST-Nummer anzeigen |
| `showLegalForm` | `bool` | `true` | Rechtsform anzeigen |
| `showAddress` | `bool` | `true` | Adresse anzeigen |
| `showStatus` | `bool` | `true` | Status-Badge anzeigen |
| `selectable` | `bool` | `false` | Klickbar machen |
| `selected` | `bool` | `false` | Als ausgewählt markieren |

**In einer Livewire-Komponente:**

```php
@foreach($companies as $company)
    <x-filament-swiss-uid-search::components.company-card 
        :company="$company"
        :selectable="true"
        wire:click="selectCompany('{{ $company->uid }}')"
    />
@endforeach
```

## Alle Field Mappings

| Methode | Beschreibung |
|---------|--------------|
| `mapUid($field)` | UID-Nummer (formatiert) |
| `mapName($field)` | Firmenname |
| `mapStreet($field)` | Strasse mit Hausnummer |
| `mapHouseNumber($field)` | Nur Hausnummer |
| `mapZipCode($field)` | Postleitzahl |
| `mapCity($field)` | Ort |
| `mapCanton($field)` | Kantonskürzel (z.B. "ZH") |
| `mapLegalForm($field)` | Rechtsform |
| `mapVatNumber($field)` | MWST-Nummer |

## Plugin Konfiguration

```php
FilamentSwissUidSearchPlugin::make()
    ->defaultLanguage('de')        // API-Sprache
    ->searchLimit(50)              // Max. Suchergebnisse
    ->showVatNumber(true)          // MWST-Nr. anzeigen
    ->showLegalForm(true)          // Rechtsform anzeigen
    ->showAddress(true)            // Adresse anzeigen
```

## Konfiguration

Veröffentlichen Sie die Konfigurationsdatei:

```bash
php artisan vendor:publish --tag=filament-swiss-uid-search-config
```

## Übersetzungen / Lokalisierung

Das Plugin unterstützt sowohl Standard-Locales als auch Schweizer Regionen:

| Locale | Sprache | Hinweis |
|--------|---------|---------|
| `de` | Deutsch | Standard |
| `de_CH` | Schweizerdeutsch | Schweizer Konventionen (ss statt ß) |
| `fr` | Französisch | Standard |
| `fr_CH` | Französisch (Schweiz) | IDE statt UID |
| `it` | Italienisch | Standard |
| `it_CH` | Italienisch (Schweiz) | IDI statt UID |
| `en` | Englisch | International |

### Schweizer Terminologie

Die Schweizer Varianten verwenden die offiziellen Begriffe:
- 🇩🇪 **UID** = Unternehmens-Identifikationsnummer
- 🇫🇷 **IDE** = Identificateur des entreprises
- 🇮🇹 **IDI** = Numero d'identificazione delle imprese

### Übersetzungen anpassen

Veröffentlichen Sie die Sprachdateien:

```bash
php artisan vendor:publish --tag=filament-swiss-uid-search-translations
```

Die Dateien werden nach `resources/lang/vendor/filament-swiss-uid-search/` kopiert.

## Styling & Theming

Das Plugin verwendet ausschliesslich **Filament's native CSS-Klassen und Tailwind**. Dadurch passt es sich automatisch an dein bestehendes Theme an:

- ✅ **Primary Color** - Buttons und Aktionen nutzen deine `primary` Farbe
- ✅ **Dark Mode** - Vollständige Unterstützung für Light/Dark Mode
- ✅ **Status Colors** - `success`, `danger`, `warning` für Validierung
- ✅ **Keine Custom CSS** - Kein Überschreiben deines Designs

Das Plugin erbt automatisch alle Theme-Anpassungen, die du in deinem `AdminPanelProvider` definiert hast.

## Abhängigkeiten

Dieses Plugin benötigt das `ecolabor/laravel-swiss-uid-search` Laravel-Package:

```bash
composer require ecolabor/laravel-swiss-uid-search
```

## Testing

```bash
composer test
```

## Lizenz

MIT License. Siehe [LICENSE](LICENSE.md) für weitere Informationen.

## Credits

- [ecolabor GmbH](https://github.com/ecolabor)
- Basierend auf dem [UID-Webservice](https://www.uid.admin.ch/) des Bundesamts für Statistik
