<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MooxTransformCommand extends Command
{
    protected $signature = 'moox:transform';

    protected $description = 'Transform Moox Packages with Filament resources';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $transformJsonPath = base_path('transform.json');
        $packagesJsonPath = base_path('packages.json');

        $transformConfig = json_decode(File::get($transformJsonPath), true);
        $packagesConfig = json_decode(File::get($packagesJsonPath), true);

        foreach ($packagesConfig as $slug => $packageData) {
            $this->info("Transforming package: {$slug}");

            $entities = $packageData['entities'] ?? [];
            $generate = $packageData['generate'] ?? [];

            foreach ($generate as $section => $isActive) {
                if (! $isActive) {
                    continue;
                }

                $this->info("Processing section: {$section}");

                foreach ($entities as $entity => $isEntityActive) {
                    if (! $isEntityActive) {
                        continue;
                    }

                    $this->info("Processing entity: {$entity}");

                    if (isset($transformConfig['copy'][$section])) {

                        foreach ($transformConfig['copy'][$section] as $source => $destination) {
                            $destinationPath = $this->replacePlaceholders($destination, $slug, $packageData, $entity);

                            // Check for wildcard ?? in source paths (like migrations)
                            $sourcePath = str_replace('%%ENTITY%%', $entity, $source);
                            $sourcePaths = glob($sourcePath);

                            foreach ($sourcePaths as $sourcePath) {
                                $directory = dirname($destinationPath);
                                if (! File::exists($directory)) {
                                    File::makeDirectory($directory, 0777, true);
                                }

                                File::copy($sourcePath, $destinationPath);

                                $fileContents = File::get($destinationPath);
                                foreach ($transformConfig['replace'] as $search => $replace) {
                                    $replaceValue = $this->replacePlaceholders($replace, $slug, $packageData, $entity);
                                    $fileContents = str_replace($search, $replaceValue, $fileContents);
                                }
                                File::put($destinationPath, $fileContents);
                            }
                        }
                    } else {
                        $this->warn("No copy entries defined for section: {$section}");
                    }

                }
            }
        }
    }

    private function replacePlaceholders($string, $slug, $packageData, $entity)
    {
        return str_replace(
            ['%%SLUG%%', '%%TITLE%%', '%%SHORTDESC%%', '%%DESCRIPTION%%', '%%ENTITY%%', '%%MODEL%%', '%%MIGRATION%%'],
            [$slug, $packageData['title'], $packageData['shortdesc'], $packageData['description'], $entity, $entity, strtolower($entity)],
            $string
        );
    }
}
