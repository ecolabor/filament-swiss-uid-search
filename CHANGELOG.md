# Changelog

All notable changes to `filament-swiss-uid-search` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.0] - 2026-01-02

### Added
- Initial release
- `UidSearch` form component for UID number input with search functionality
- `CompanySearch` form component for searching companies by name
- `SearchUidAction` header action for quick company lookup
- `UidSearchModal` Livewire component for interactive search
- Automatic form field population (name, street, zip code, city)
- Multi-language support:
  - German (`de`, `de_CH`)
  - English (`en`)
  - French (`fr`, `fr_CH`) - uses "IDE" terminology
  - Italian (`it`, `it_CH`) - uses "IDI" terminology
- Full Filament v4 compatibility
- Plugin configuration via `FilamentSwissUidSearchPlugin`
