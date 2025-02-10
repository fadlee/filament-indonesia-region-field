<?php

namespace Fadlee\FilamentIndonesiaRegionField\Enums;

enum RegionLevel: int
{
    case LEVEL_1_PROVINCE = 1;
    case LEVEL_2_CITY = 2;
    case LEVEL_3_DISTRICT = 3;
    case LEVEL_4_VILLAGE = 4;

    public function getDataType(): string
    {
        return match($this) {
            self::LEVEL_1_PROVINCE => 'provinces',
            self::LEVEL_2_CITY => 'cities',
            self::LEVEL_3_DISTRICT => 'districts',
            self::LEVEL_4_VILLAGE => 'villages',
        };
    }

    public static function fromDataType(string $type): ?self
    {
        return match($type) {
            'provinces' => self::LEVEL_1_PROVINCE,
            'cities' => self::LEVEL_2_CITY,
            'districts' => self::LEVEL_3_DISTRICT,
            'villages' => self::LEVEL_4_VILLAGE,
            default => null,
        };
    }
}
