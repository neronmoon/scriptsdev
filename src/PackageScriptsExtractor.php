<?php

namespace ScriptsDev;


use Composer\IO\IOInterface;
use Composer\Package\CompletePackage;
use Composer\Package\PackageInterface;

class PackageScriptsExtractor
{
    /**
     * @var IOInterface
     */
    private $io;

    protected static $warningPrinted = false;

    public function __construct(IOInterface $io)
    {
        $this->io = $io;
    }

    /**
     * @param PackageInterface $package
     * @return array
     */
    public function extract(PackageInterface $package)
    {
        if ($package instanceof CompletePackage && isset($_SERVER['argv']) && in_array('--no-dev', $_SERVER['argv'], true)) {
            return array();
        }

        $devScripts = array();
        $config = json_decode(file_get_contents(getcwd() . '/composer.json'), true);
        if (isset($config['scripts-dev']) && is_array($config['scripts-dev'])) {
            if (!static::$warningPrinted) {
                $this->io->writeError(
                    "<warning>You're using deprecated way to define development-only scripts.
Please, move commands under `scripts-dev` directive into `extra` field.
See https://github.com/neronmoon/scriptsdev/blob/master/README.md for more details.</warning>"
                );
                static::$warningPrinted = true;
            }
            $devScripts = array_merge_recursive($devScripts, $config['scripts-dev']);
        }

        $extra = $package->getExtra();
        if (isset($extra['scripts-dev']) && is_array($extra['scripts-dev'])) {
            $devScripts = array_merge_recursive($devScripts, $extra['scripts-dev']);
        }

        foreach ($devScripts as $event => &$listeners) {
            $listeners = (array)$listeners;
        }

        return $devScripts;
    }
}
