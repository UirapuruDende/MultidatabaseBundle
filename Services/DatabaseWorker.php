<?php
namespace Dende\MultidatabaseBundle\Services;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Exception;
use Dende\MultidatabaseBundle\Connection\ConnectionWrapper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\Output;

final class DatabaseWorker
{
    const QUERY_CREATE_DB = 'CREATE DATABASE IF NOT EXISTS `{{name}}`;';
    const QUERY_DROP_DB = 'DROP DATABASE IF EXISTS `{{name}}`;';

    /**
     * @var string $dbName
     */
    public $dbName;

    /**
     * @var string $username
     */
    private $username;

    /**
     * @var string $password
     */
    private $password;

    /**
     * @var string $clubDatabaseNameTemplate
     */
    private $clubDatabaseNameTemplate;

    /**
     * @var Slugify $slugifier
     */
    private $slugifier;

    /**
     * @var EntityManager $clubEntityManager
     */
    private $clubEntityManager;

    /**
     * @var EntityManager $clubEntityManager
     */
    private $defaultEntityManager;

    /**
     * @var ConnectionWrapper $clubConnection
     */
    private $clubConnection;

    /**
     * @var Connection $connection
     */
    private $defaultConnection;

    /**
     * DatabaseWorker constructor.
     * @param $username
     * @param $password
     */
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Setup database for given club name
     * @param $clubName
     */
    public function setupDatabase($options)
    {
        $this->options = $options;

        $this->createDatabase();
        $this->updateClubConnection();
        $this->createSchema();

        return [$this->dbName, $this->username, $this->password];
    }

    /**
     * Creates database
     */
    public function createDatabase()
    {
        $this->defaultConnection->exec(
            $this->getCreateDatabaseQuery($this->dbName)
        );
    }

    /**
     * Creates schema in existing database
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    public function createSchema()
    {
        $metadatas = $this->clubEntityManager->getMetadataFactory()->getAllMetadata();

        if (!empty($metadatas)) {
            $tool = new SchemaTool($this->clubEntityManager);
            $tool->createSchema($metadatas);
        } else {
            throw new Exception('No Metadata Classes to process.');
        }
    }

    /**
     * @param $dbName
     * @return string
     */
    private function getCreateDatabaseQuery($dbName)
    {
        $query = str_replace('{{name}}', $dbName, self::QUERY_CREATE_DB);

        return $query;
    }

    private function getDropDatabaseQuery($dbname)
    {
        $query = str_replace('{{name}}', $dbname, self::QUERY_DROP_DB);

        return $query;
    }

    /**
     * @param Connection $defaultConnection
     */
    public function setDefaultConnection($defaultConnection)
    {
        $this->defaultConnection = $defaultConnection;
    }

    /**
     * @param ConnectionWrapper $connection
     */
    public function setClubConnection(Connection $connection)
    {
        $this->clubConnection = $connection;
    }

    /**
     * @param EntityManager $entityManager
     */
    public function setClubEntityManager(EntityManager $entityManager)
    {
        $this->clubEntityManager = $entityManager;
    }

    /**
     * @param EntityManager $defaultEntityManager
     */
    public function setDefaultEntityManager($defaultEntityManager)
    {
        $this->defaultEntityManager = $defaultEntityManager;
    }

    /**
     * @param mixed $slugifier
     */
    public function setSlugifier(Slugify $slugifier)
    {
        $this->slugifier = $slugifier;
    }

    private function updateClubConnection($club = null)
    {
        if ($club !== null) {
            $db = $club->getDatabase();
            $this->dbName = $db[CredentialsStorage::PARAM_BASE];
            $this->username = $db[CredentialsStorage::PARAM_USER];
            $this->password = $db[CredentialsStorage::PARAM_PASS];
        }

        $this->clubConnection->forceSwitch(
            $this->dbName,
            $this->username,
            $this->password
        );
    }

    public function migrate(Command $command, Output $output, ArgvInput $input)
    {
        $clubs = $this->defaultEntityManager->getRepository('ClubBundle:Club')->findAll();
        $count = count($clubs);

        $input->setOption('em', 'tenant');
        $input->setInteractive(false);

        foreach ($clubs as $i => $club) {
            $this->updateClubConnection($club);

            $output->writeln(sprintf(
                'Migrating database %s (%d/%d)',
                $club->getDatabase()[CredentialsStorage::PARAM_BASE],
                $i + 1,
                $count
            ));

            $clonedCommand = clone($command);
            $clonedCommand->run($input, $output);
        }
    }

    public function generateMigrations(Command $command, Output $output, ArgvInput $input)
    {
        $clubs = $this->defaultEntityManager->getRepository('ClubBundle:Club')->findAll();
        $singleClub = array_pop($clubs);

        $this->updateClubConnection($singleClub);

        $input->setOption('em', 'tenant');
        $input->setInteractive(false);

        $output->writeln(sprintf(
            'Generating migration with %s as reference',
            $singleClub->getDatabase()[CredentialsStorage::PARAM_BASE]
        ));

        $command->run($input, $output);
    }
}
