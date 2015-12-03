<?php
namespace Dende\MultidatabaseBundle\Services;

/**
 * Interface TenantProviderInterface
 * @package Dende\MultidatabaseBundle\Services
 */
interface TenantProviderInterface
{
    /**
     * @param string|null $tenant
     * @return Tenant
     */
    public function getTenant($tenant = null);
}