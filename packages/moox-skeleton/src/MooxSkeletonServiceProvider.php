<?php

declare(strict_types=1);

namespace Moox\Skeleton;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Illuminate\View\Compilers\BladeCompiler;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MooxSkeletonServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('moox-skeleton')
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations()
            ->hasMigrations('');
    }
}
