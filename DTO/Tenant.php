<?php
namespace Dende\MultidatabaseBundle\DTO;

/**
 * Class Tenant
 * @package Dende\MultidatabaseBundle\DTO
 */
class Tenant
{
    /**
     * @var string
     */
    public $host = 'localhost';

    /**
     * @var string
     */
    public $databaseName;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;

    /**
     * Tenant constructor.
     * @param string $host
     * @param string $databaseName
     * @param string $username
     * @param string $password
     */
    public function __construct($host = 'localhost', $databaseName, $username, $password)
    {
        $this->host = $host;
        $this->databaseName = $databaseName;
        $this->username = $username;
        $this->password = $password;
    }
}