<?php

namespace ScriptsDev;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Package\AliasPackage;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;

class Plugin implements PluginInterface, Capable
{
    public function getCapabilities()
    {
        return array(
            'Composer\Plugin\Capability\CommandProvider' => 'ScriptsDev\DevScriptsCommandProvider',
        );
    }

    public function activate(Composer $composer, IOInterface $io)
    {
        $package = $composer->getPackage();
        // If we have extra.branch-alias, package will be an instanceof RootAliasPackage instead of RootPackage
        if ($package instanceof AliasPackage) {
            $package = $package->getAliasOf();
        }

        $extractor = new PackageScriptsExtractor($io);
        $devScripts = $extractor->extract($package);

        $package->setScripts(array_merge_recursive($package->getScripts(), $devScripts));
    }

    public function deactivate(Composer $composer, IOInterface $io)
    {
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
    }
}
