<?php

namespace Fadlee\FilamentIndonesiaRegionField;

use Composer\InstalledVersions;
use Fadlee\FilamentIndonesiaRegionField\Enums\RegionLevel;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\LaravelPackageTools\Package;

class FilamentIndonesiaRegionFieldServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-indonesia-region-field';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasViews();
    }

    public function register(): void
    {
        $currentPackageVersion = $this->getPackageVersion();

        Route::get('/indonesia-region-field/data/{type}', function ($type) {
            // check if type is valid and get the region level
            $level = RegionLevel::fromDataType($type);
            if ($level === null) {
                abort(404);
            }

            return response()
                ->file(__DIR__ . '/../resources/data/' . $level->getDataType() . '.csv', [
                    'Content-Type' => 'text/plain'
                ]);
        })->name('indonesia-region-field.data');
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'indonesia-region-field');

        FilamentAsset::register([
            Js::make('indonesia-region-field', __DIR__ . '/../resources/js/indonesia-region-field.js'),
        ], 'indonesia-region-field');

        // Inject QR code scanner modal into the end of page
        FilamentView::registerRenderHook(
            PanelsRenderHook::PAGE_START,
            fn () => view('indonesia-region-field::loader')
        );
    }

    protected function getPackageVersion(): string
    {
        try {
            return InstalledVersions::getPrettyVersion('fadlee/filament-indonesia-region-field') ?? '1.0.0';
        } catch (\Exception $e) {
            return '1.0.0';
        }
    }
}
