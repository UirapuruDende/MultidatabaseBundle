<?php
namespace Dende\MultidatabaseBundle\Listener;

use Dende\MultidatabaseBundle\DTO\Tenant;
use Dende\MultidatabaseBundle\Services\TenantProviderInterface;
use Dende\MultidatabaseBundle\Connection\ConnectionWrapper;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class DatabaseSwitcher
 * @package Dende\MultidatabaseBundle
 */
class DatabaseSwitcher
{
    /**
     * @var ConnectionWrapper
     */
    private $tenantConnection;

    /**
     * @var TenantProviderInterface
     */
    private $tenantProvider;

    /**
     * DatabaseSwitcher constructor.
     * @param ConnectionWrapper $tenantConnection
     * @param TenantProviderInterface $tenantProvider
     */
    public function __construct(ConnectionWrapper $tenantConnection, TenantProviderInterface $tenantProvider)
    {
        $this->tenantConnection = $tenantConnection;
        $this->tenantProvider = $tenantProvider;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        /** @var Tenant $tenant */
        $tenant = $this->tenantProvider->getTenant();
        $this->tenantConnection->forceSwitch($tenant->host, $tenant->databaseName, $tenant->username, $tenant->password);
    }
}
