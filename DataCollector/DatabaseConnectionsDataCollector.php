<?php
namespace Dende\MultidatabaseBundle\DataCollector;

use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class DatabaseConnectionsDataCollector extends DataCollector
{
    /**
     * @var Connection $defaultConnection
     */
    private $defaultConnection;

    /**
     * @var Connection $defaultConnection
     */
    private $clubConnection;

    public function __construct(Connection $defaultConnection, Connection $clubConnection)
    {
        $this->clubConnection = $clubConnection;
        $this->defaultConnection = $defaultConnection;
    }

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data['default'] = $this->defaultConnection->getDatabase();

        if ($this->clubConnection->isConnected()) {
            $this->data['tenant'] = $this->clubConnection->getDatabase();
        } else {
            $this->data['tenant'] = 'disconnected';
        }
    }

    public function getDefault()
    {
        return $this->data['default'];
    }

    public function getClub()
    {
        return $this->data['tenant'];
    }

    public function getName()
    {
        return 'database_connections';
    }
}
