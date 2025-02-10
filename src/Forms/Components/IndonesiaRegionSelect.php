<?php

namespace Fadlee\FilamentIndonesiaRegionField\Forms\Components;

use Filament\Forms\Components\Select;

class IndonesiaRegionSelect extends Select
{
    // use default filament text input view
    protected string $view = 'indonesia-region-field::select';

    protected function setUp(): void
    {
        parent::setUp();

        // Set default properties for the BarcodeInput
        $this->label('Indonesia Region Select')
            ->native(false)
            ->searchable()
            ->searchDebounce(300)
            ->getSearchResultsUsing(fn (string $search) => [])
            ->getOptionLabelUsing(fn (string $value) => $value)
            ->extraInputAttributes(['data-indonesia-region-field' => '1'])
            ->placeholder('Cari Desa / Kelurahan...');
    }
}
