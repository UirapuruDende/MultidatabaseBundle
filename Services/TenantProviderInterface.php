<?php
namespace Dende\MultidatabaseBundle\Services;

/**
 * Interface TenantProviderInterface
 * @package Dende\MultidatabaseBundle\Services
 */
interface TenantProviderInterface
{
    /**
     * @param string|null $subdomain
     * @return Tenant
     */
    public function getTenant($subdomain = null);
}