<?php
namespace Dende\MultidatabaseBundle\Listener;

use Dende\MultidatabaseBundle\DTO\Tenant;
use Dende\MultidatabaseBundle\Services\TenantProviderInterface;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\ORM\EntityRepository;
use Dende\MultidatabaseBundle\Connection\ConnectionWrapper;
use Dende\MultidatabaseBundle\Exception\ClubNotFoundException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputOption;

final class ClubConnectionCommandListener
{
    /**
     * @var ConnectionWrapper
     */
    private $connectionWrapper;

    /**
     * @var AbstractSchemaManager
     */
    private $schemaManager;

    /**
     * @var
     */
    private $config;

    /**
     * @var TenantProviderInterface
     */
    private $tenantProvider;

    /**
     * ClubConnectionCommandListener constructor.
     * @param TenantProviderInterface $tenantProvider
     * @param ConnectionWrapper $connectionWrapper
     * @param AbstractSchemaManager $schemaManager
     * @param array $config
     */
    public function __construct(TenantProviderInterface $tenantProvider, ConnectionWrapper $connectionWrapper, AbstractSchemaManager $schemaManager, $config)
    {
        $this->tenantProvider = $tenantProvider;
        $this->connectionWrapper = $connectionWrapper;
        $this->schemaManager = $schemaManager;
        $this->config = $config;
    }

    /**
     * @param ConsoleCommandEvent $event
     * @throws ClubNotFoundException
     */
    public function onConsoleCommand(ConsoleCommandEvent $event)
    {
        $command = $event->getCommand();
        $input = $event->getInput();
        $paramName = $this->config['parameterName'];

        if (!$this->isProperCommand($command)) {
            return;
        }

        $command->getDefinition()->addOption(
            new InputOption($paramName, null, InputOption::VALUE_OPTIONAL, $this->config['parameterDescription'], null)
        );

        $input->bind($command->getDefinition());

        if(is_null($input->getOption($paramName))) {
            $event->getOutput()->write('<error>default:</error> ');
            return;
        }

        $tenantName = $input->getOption($paramName);

        if ($tenantName === null) {
            $event->getOutput()->write('<error>default:</error> ');
            return;
        }

        $input->setOption('em', $this->config['modelManagerName']);
        $command->getDefinition()->getOption('em')->setDefault($this->config['modelManagerName']);

        /** @var Tenant $tenant */
        $tenant = $this->tenantProvider->getTenant($tenantName);

        $this->connectionWrapper->forceSwitch($tenant->host, $tenant->databaseName, $tenant->username, $tenant->password);

        $event->getOutput()->writeln(
            sprintf('<error>%s@%s:</error> ', $tenant->username, $tenant->databaseName)
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
