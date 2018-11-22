<?php

namespace ScriptsDev;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\Capability\CommandProvider;

class DevScriptsCommandProvider implements CommandProvider
{
    /**
     * @var Composer
     */
    private $composer;
    /**
     * @var IOInterface
     */
    private $io;

    public function __construct(array $args)
    {
        if (!$args['composer'] instanceof Composer) {
            throw new \RuntimeException('Expected a "composer" key');
        }
        if (!$args['io'] instanceof IOInterface) {
            throw new \RuntimeException('Expected an "io" key');
        }
        $this->composer = $args['composer'];
        $this->io = $args['io'];
    }

    public function getCommands()
    {
        $package = $this->composer->getPackage();
        $extractor = new PackageScriptsExtractor($this->io);
        $devScripts = $extractor->extract($package);
        $commands = array();
        foreach ($devScripts as $name => $cmd) {
            $commands[] = $this->buildCommand($name);
        }
        return $commands;
    }

    /**
     * @param $name
     * @return DevScriptProxyCommand
     */
    private function buildCommand($name)
    {
        $command = new DevScriptProxyCommand($name);
        $command->setName($this->replaceName($command->getName(), $name));
        $command->setDescription($this->replaceName($command->getDescription(), $name));
        return $command;
    }

    private function replaceName($string, $replacement)
    {
        return str_replace('{name}', $replacement, $string);
    }
}
