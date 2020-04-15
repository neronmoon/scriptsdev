<?php

namespace ScriptsDev;


use Composer\Command\BaseCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DevScriptProxyCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('{name}')
            ->setDescription('Run the {name} defined in composer.json.')
            ->setDefinition(array(
                new InputArgument('script', InputArgument::OPTIONAL, ''),
                new InputArgument('args', InputArgument::IS_ARRAY | InputArgument::OPTIONAL, ''),
                new InputOption('timeout', null, InputOption::VALUE_REQUIRED,
                    'Sets script timeout in seconds, or 0 for never.'),
                new InputOption('dev', null, InputOption::VALUE_NONE, 'Sets the dev mode.'),
                new InputOption('no-dev', null, InputOption::VALUE_NONE, 'Disables the dev mode.'),
                new InputOption('list', 'l', InputOption::VALUE_NONE, 'List scripts.'),
            ));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $args = array(
            'command' => 'run-script',
            'script' => $this->getName(),
            'args' => $input->getArgument('args'),
            '--timeout' => $input->getOption('timeout') ?: '0',
            '--dev' => $input->getOption('dev'),
            '--no-dev' => $input->getOption('no-dev'),
            '--list' => $input->getOption('list'),
        );

        $command = $this->getApplication()->find('run-script');

        $arrayInput = new ArrayInput($args);
        $statusCode = $command->run($arrayInput, $output);
        return is_numeric($statusCode) ? (int) $statusCode : 0;
    }
}
