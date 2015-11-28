<?php
namespace Dende\MultidatabaseBundle\Listener;

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
        $this->tenantConnection->forceSwitch(
            $this->tenantProvider->getDatabaseHost(),
            $this->tenantProvider->getDatabaseName(),
            $this->tenantProvider->getUsername(),
            $this->tenantProvider->getPassword()
        );
    }
}
