<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MooxPrepareCommand extends Command
{
    protected $signature = 'moox:prepare';

    protected $description = 'Prepare Moox Packages';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $prepareJsonPath = base_path('prepare.json');
        $packagesJsonPath = base_path('packages.json');

        $prepareConfig = json_decode(File::get($prepareJsonPath), true);
        $packagesConfig = json_decode(File::get($packagesJsonPath), true);

        foreach ($packagesConfig as $slug => $packageData) {
            foreach ($prepareConfig['copy'] as $source => $destination) {
                $destination = $this->replacePlaceholders($destination, $slug, $packageData);

                $directory = dirname($destination);
                if (! File::exists($directory)) {
                    File::makeDirectory($directory, 0777, true);
                }

                File::copy($source, $destination);

                $fileContents = File::get($destination);
                foreach ($prepareConfig['replace'] as $search => $replace) {
                    $replaceValue = $this->replacePlaceholders($replace, $slug, $packageData);
                    $fileContents = str_replace($search, $replaceValue, $fileContents);
                }
                File::put($destination, $fileContents);
            }
        }
    }

    private function replacePlaceholders($string, $slug, $packageData)
    {
        $resourcesString = implode(', ', array_keys($packageData['resources']));

        return str_replace(
            ['%%SLUG%%', '%%TITLE%%', '%%SHORTDESC%%', '%%DESCRIPTION%%', '%%RESOURCE%%'],
            [$slug, $packageData['title'], $packageData['shortdesc'], $packageData['description'], $resourcesString],
            $string
        );
    }
}
