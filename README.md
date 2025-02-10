# Filament Indonesia Region Field

A custom form field component for Filament PHP that adds Indonesia region selection capabilities to your forms. This package provides a single select input field that can handle 4 levels of Indonesia administrative regions (Province, Regency/City, District, and Village).

## Features

- Seamless integration with Filament forms
- Single input field for selecting Indonesia regions
- Support for 4 administrative levels:
  - Province (Provinsi)
  - Regency/City (Kabupaten/Kota)
  - District (Kecamatan)
  - Village (Desa/Kelurahan)
- Client-side fuzzy search for better performance
- No additional server requests for searching
- Extends Filament's native select component

## Requirements

- PHP 8.1 or higher
- Laravel 10.0 or higher
- Filament 3.x

## Installation

You can install the package via composer:

```bash
composer require fadlee/filament-indonesia-region-field
```

## Usage

1. Import the IndonesiaRegionSelect component and RegionLevel enum in your Filament resource or form:

```php
use Fadlee\FilamentIndonesiaRegionField\Forms\Components\IndonesiaRegionSelect;
use Fadlee\FilamentIndonesiaRegionField\Enums\RegionLevel;
```

2. Add the field to your form:

```php
public static function form(Form $form): Form
{
    return $form
        ->schema([
            IndonesiaRegionSelect::make('region')
                ->label('Region')
                ->placeholder('Select region...'),
            // ... other fields
        ]);
}
```

3. Using with specific level:

```php
public static function form(Form $form): Form
{
    return $form
        ->schema([
            IndonesiaRegionSelect::make('region')
                ->label('Region')
                ->level(RegionLevel::LEVEL_3_DISTRICT)
                ->placeholder('Select district...'),
        ]);
}
```

Available levels:
- `RegionLevel::LEVEL_1_PROVINCE`
- `RegionLevel::LEVEL_2_CITY`
- `RegionLevel::LEVEL_3_DISTRICT`
- `RegionLevel::LEVEL_4_VILLAGE`

4. Using in Filament action modals:

```php
use Filament\Actions\Action;

public static function getActions(): array
{
    return [
        Action::make('selectRegion')
            ->form([
                IndonesiaRegionSelect::make('region')
                    ->label('Select Region')
                    ->required(),
            ])
            ->action(function (array $data) {
                // Process the selected region
                // $data['region'] contains the selected region code
            }),
    ];
}
```

## How it Works

The package extends Filament's native select component to provide a seamless Indonesia region selection experience. Key features include:

1. **Client-side Search**: All searching is performed on the client side using fuzzy search, eliminating the need for additional server requests and providing instant feedback.

2. **Data Loading**: Region data is loaded once and cached on the client side, making subsequent searches and filtering operations extremely fast.

3. **User Experience**: The component provides immediate response to user input since all operations are performed locally in the browser.

## Technical Implementation

The Indonesia region selector is built on top of Filament's select component with these enhancements:

1. **Data Structure**: Uses a comprehensive database of Indonesia administrative regions up to village level.

2. **Search Implementation**: Implements client-side fuzzy search for better performance and user experience.

3. **Value Management**: Handles the storage and retrieval of region codes while displaying human-readable region names in the interface.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This package is open-sourced software licensed under the MIT license.
