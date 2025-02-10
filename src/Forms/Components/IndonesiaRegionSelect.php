<?php

namespace Fadlee\FilamentIndonesiaRegionField\Forms\Components;

use Filament\Forms\Components\Select;
use Fadlee\FilamentIndonesiaRegionField\Enums\RegionLevel;

use function PHPUnit\Framework\matches;

class IndonesiaRegionSelect extends Select
{
    // use default filament text input view
    protected string $view = 'indonesia-region-field::select';

    protected RegionLevel $level = RegionLevel::LEVEL_4_VILLAGE;

    public function level(RegionLevel $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getLevel(): RegionLevel
    {
        return $this->level;
    }

    public function getPlaceholder(): string|null
    {
        return match ($this->getLevel()) {
            RegionLevel::LEVEL_4_VILLAGE => 'Cari Desa / Kelurahan...',
            RegionLevel::LEVEL_3_DISTRICT => 'Cari Kecamatan...',
            RegionLevel::LEVEL_2_CITY => 'Cari Kota / Kabupaten...',
            RegionLevel::LEVEL_1_PROVINCE => 'Cari Provinsi...',
            default => 'Cari wilayah...',
        };
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Set default properties for the BarcodeInput
        $this->label('Indonesia Region Select')
            ->native(false)
            ->searchable()
            ->searchDebounce(300)
            ->getSearchResultsUsing(fn (string $search) => [])
            ->getOptionLabelUsing(fn (string $value) => $value);
    }
}
