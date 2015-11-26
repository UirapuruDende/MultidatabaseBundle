<?php
namespace Dende\MultidatabaseBundle\Entity;

/**
 * Class Tenant
 * @package Dende\MultidatabaseBundle\Entity
 */
class Tenant
{
    /**
     * @var
     */
    public $id;

    /**
     * @var
     */
    public $host;

    /**
     * @var
     */
    public $dbname;

    /**
     * @var
     */
    public $user;

    /**
     * @var
     */
    public $password;

    /**
     * @var
     */
    public $subdomain;
}