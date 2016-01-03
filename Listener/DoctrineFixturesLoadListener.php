<?php
namespace Dende\MultidatabaseBundle\Listener;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\ArgvInput;

final class DoctrineFixturesLoadListener
{

    /**
     * @var string
     */
    private $entityManagerName;

    /**
     * @var array
     */
    private $fixtures;

    /**
     * @var string
     */
    private $parameterName;

    /**
     * DoctrineFixturesLoadListener constructor.
     * @param string $entityManagerName
     * @param $parameterName
     * @param array $fixtures
     */
    public function __construct($entityManagerName, $parameterName, array $fixtures)
    {
        $this->entityManagerName = $entityManagerName;
        $this->parameterName = $parameterName;
        $this->fixtures = $fixtures;
    }

    /**
     * @param ConsoleCommandEvent $event
     */
    public function onConsoleCommand(ConsoleCommandEvent $event)
    {
        $command = $event->getCommand();

        if (!$this->isProperCommand($command)) {
            return;
        }

        $definition = $command->getDefinition();

        $input = $event->getInput();
//        $input->bind($command->getDefinition());

        if (is_null($input->getOption($this->parameterName))) {
            $event->getOutput()->writeln(sprintf('Using <info>standard</info> fixtures: <info>%s</info>', implode(',', $this->fixtures['default'])));
            $definition->getOption('fixtures')->setDefault($this->fixtures['default']);
        } else {
            $event->getOutput()->writeln(sprintf('Using <info>custom</info> fixtures: <info>%s</info>', implode(',', $this->fixtures['tenant'])));
            $definition->getOption('fixtures')->setDefault($this->fixtures['tenant']);
            $definition->getOption('em')->setDefault($this->entityManagerName);
        }
    }

    /**
     * @param Command $command
     * @return bool
     */
    private function isProperCommand(Command $command)
    {
        return in_array($command->getName(), [
            'doctrine:fixtures:load',
        ]);
    }
}
