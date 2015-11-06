<?php

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

class Plugin implements PluginInterface
{
	public function activate(Composer $composer, IOInterface $io)
	{
		$config = json_decode(file_get_contents(getcwd() . '/composer.json'), true);
		if (!in_array('--no-dev', $_SERVER['argv']) && isset($config['scripts-dev'])) {
			$scripts = array_merge_recursive(
				$composer->getPackage()->getScripts(),
				$config['scripts-dev']
			);
			$composer->getPackage()->setScripts($scripts);
		}
	}
}
