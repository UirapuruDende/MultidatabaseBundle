<?php
namespace Dende\MultidatabaseBundle\Connection;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Event\ConnectionEventArgs;
use Doctrine\DBAL\Events;

/**
 * Class ConnectionWrapper
 * @package Dende\MultidatabaseBundle\Connection
 */
class ConnectionWrapper extends Connection
{
    /**
     * @var bool
     */
    private $isConnected = false;

    /**
     * @param array $params
     * @param Driver $driver
     * @param Configuration|null $config
     * @param EventManager|null $eventManager
     */
    public function __construct(
        array $params, Driver $driver, Configuration $config = null, EventManager $eventManager = null
    ) {
        $this->params = $params;

        parent::__construct($params, $driver, $config, $eventManager);
    }

    /**
     * @param $host
     * @param $dbname
     * @param $username
     * @param $password
     */
    public function forceSwitch($host, $dbname, $username, $password)
    {
        if ($this->isConnected()) {
            $this->close();
        }

        $this->params['host'] = $host;
        $this->params['dbname'] = $dbname;
        $this->params['user'] = $username;
        $this->params['password'] = $password;

        $this->connect();
    }

    /**
     * {@inheritDoc}
     */
    public function connect()
    {
        if ($this->isConnected()) {
            return true;
        }

        $this->_conn = $this->_driver->connect(
            $this->params,
            $this->params['user'],
            $this->params['password'],
            $this->params['driverOptions']
        );

        if ($this->_eventManager->hasListeners(Events::postConnect)) {
            $eventArgs = new ConnectionEventArgs($this);
            $this->_eventManager->dispatchEvent(Events::postConnect, $eventArgs);
        }

        $this->isConnected = true;

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function isConnected()
    {
        return $this->isConnected;
    }

    /**
     * {@inheritDoc}
     */
    public function close()
    {
        if ($this->isConnected()) {
            parent::close();
            $this->isConnected = false;
        }
    }
}
