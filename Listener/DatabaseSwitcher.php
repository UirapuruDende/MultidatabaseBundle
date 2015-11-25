<?php
namespace Dende\MultidatabaseBundle\Listener;

use Doctrine\ORM\EntityManager;
use Dende\MultidatabaseBundle\Services\SubdomainProvider;
use Dende\MultidatabaseBundle\Services\SubdomainProviderInterface;
use Gyman\Bundle\ClubBundle\Entity\Club;
use Gyman\Bundle\ClubBundle\Entity\Subdomain;
use Dende\MultidatabaseBundle\Connection\ConnectionWrapper;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class DatabaseSwitcher
 * @package Dende\MultidatabaseBundle
 */
class DatabaseSwitcher
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ConnectionWrapper
     */
    private $clubConnection;

    /**
     * @var SubdomainProvider
     */
    private $subdomainProvider;

    /**
     * DatabaseSwitcher constructor.
     * @param SubdomainProviderInterface $provider
     * @param EntityManager $entityManager
     * @param ConnectionWrapper $connectionWrapper
     * @internal param $baseUrl
     */
    public function __construct(SubdomainProviderInterface $provider, EntityManager $entityManager, ConnectionWrapper $connectionWrapper)
    {
        $this->subdomainProvider = $provider;
        $this->entityManager = $entityManager;
        $this->clubConnection = $connectionWrapper;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $subdomain = $this->subdomainProvider->getSubdomain();

        /** @var Tenant $tenant */
        $tenant = $this->entityManager->getRepository('DendeMultidatabaseBundle:Tenant')->findOneBySubdomain($subdomain);

        if (!$tenant) {
            throw new NotFoundHttpException(sprintf('Subdomain "%s" not found or club not registered.', $subdomain));
        }

        $this->clubConnection->forceSwitch(
            $tenant->dbname,
            $tenant->user,
            $tenant->password
        );
    }
}
