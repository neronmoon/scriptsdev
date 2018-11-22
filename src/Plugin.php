<?php

namespace ScriptsDev;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;
use Composer\Plugin\Capability\CommandProvider;

class Plugin implements PluginInterface, Capable
{
    public function getCapabilities()
    {
        return array(
            CommandProvider::class => DevScriptsCommandProvider::class,
        );
    }

    public function activate(Composer $composer, IOInterface $io)
    {
        $package = $composer->getPackage();

        $extractor = new PackageScriptsExtractor($io);
        $devScripts = $extractor->extract($package);

        $package->setScripts(array_merge_recursive($package->getScripts(), $devScripts));
    }
}
