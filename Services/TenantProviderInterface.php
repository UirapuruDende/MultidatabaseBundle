<?php
namespace Dende\MultidatabaseBundle\Services;

/**
 * Interface TenantProviderInterface
 * @package Dende\MultidatabaseBundle\Services
 */
interface TenantProviderInterface
{
    /**
     * @return string
     */
    public function getDatabaseHost();

    /**
     * @return string
     */
    public function getDatabaseName();

    /**
     * @return string
     */
    public function getUsername();

    /**
     * @return string
     */
    public function getPassword();
}