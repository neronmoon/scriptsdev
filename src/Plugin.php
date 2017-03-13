<?php

namespace ScriptsDev;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Package\CompletePackage;
use Composer\Plugin\PluginInterface;

class Plugin implements PluginInterface
{
	public function activate(Composer $composer, IOInterface $io)
	{
		if (in_array('--no-dev', $_SERVER['argv'], true)) {
			return;
		}

		$devScripts = array();
		$config = json_decode(file_get_contents(getcwd() . '/composer.json'), true);
		if (isset($config['scripts-dev']) && is_array($config['scripts-dev'])) {
			$io->writeError("You're using deprecated way to define development-only scripts. 
Please, move commands under `scripts-dev` directive into `extra` field.
See README.md for more details.");
			$devScripts = array_merge_recursive($devScripts, $config['scripts-dev']);
		}

		$package = $composer->getPackage();

		$extra = $package->getExtra();
		if (isset($extra['scripts-dev']) && is_array($extra['scripts-dev'])) {
			$devScripts = array_merge_recursive($devScripts, $extra['scripts-dev']);
		}

		if ($package instanceof CompletePackage) {
			foreach ($devScripts as $event => &$listeners) {
				$listeners = (array) $listeners;
			}
			$package->setScripts(array_merge_recursive($package->getScripts(), $devScripts));
		}
	}
}
