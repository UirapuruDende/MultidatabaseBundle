<?php
namespace Dende\MultidatabaseBundle\Listener;

use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\ORM\EntityRepository;
use Gyman\Bundle\ClubBundle\Entity\Club;
use Gyman\Bundle\ClubBundle\Entity\Subdomain;
use Dende\MultidatabaseBundle\Connection\ConnectionWrapper;
use Dende\MultidatabaseBundle\Exception\ClubNotFoundException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputOption;

final class ClubConnectionCommandListener
{
    /**
     * @var EntityRepository
     */
    private $clubRepository;

    /**
     * @var ConnectionWrapper
     */
    private $connectionWrapper;

    /**
     * @var AbstractSchemaManager
     */
    private $schemaManager;

    /**
     * @param ConsoleCommandEvent $event
     * @throws ClubNotFoundException
     */
    public function onConsoleCommand(ConsoleCommandEvent $event)
    {
        $command = $event->getCommand();

        if (!$this->isProperCommand($command)) {
            return;
        }

        $command->getApplication()->getDefinition()->addOption(
            new InputOption('tenant', null, InputOption::VALUE_OPTIONAL, 'tenant subdomain', null)
        );
        $command->mergeApplicationDefinition();

        $input = new ArgvInput();
        $input->bind($command->getDefinition());
        $tenantName = $input->getOption('tenant');

        if ($tenantName === null) {
            $event->getOutput()->write('<error>redskull:</error> ');

            return;
        }

        $input->setOption('em', 'tenant');
        $command->getDefinition()->getOption('em')->setDefault('tenant');

        if (!$this->schemaManager->tablesExist(['tenants'])) {
            return;
        }

        /** @var Tenant $tenant */
        $tenant = $this->clubRepository->findOneBySubdomain($tenantName);

        if (!$tenant) {
            throw new ClubNotFoundException($tenantName);
        }

        $this->connectionWrapper->forceSwitch(
            $tenant->dbname,
            $tenant->user,
            $tenant->password
        );

        $event->getOutput()->writeln(
            sprintf('<error>%s@%s:</error> ', $tenant->user, $tenant->dbname)
        );
    }

    /**
     * @param Command $command
     * @return bool
     */
    private function isProperCommand(Command $command)
    {
        return in_array($command->getName(), [
            'doctrine:schema:update',
            'doctrine:schema:create',
            'doctrine:schema:drop',
            'doctrine:fixtures:load',
        ]);
    }

    /**
     * @param EntityRepository $clubRepository
     */
    public function setClubRepository(EntityRepository $clubRepository)
    {
        $this->clubRepository = $clubRepository;
    }

    /**
     * @param ConnectionWrapper $clubConnection
     */
    public function setConnectionWrapper(ConnectionWrapper $clubConnection)
    {
        $this->connectionWrapper = $clubConnection;
    }

    /**
     * @param AbstractSchemaManager $schemaManager
     */
    public function setSchemaManager($schemaManager)
    {
        $this->schemaManager = $schemaManager;
    }
}
