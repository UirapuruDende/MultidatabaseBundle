<?php
namespace Dende\MultidatabaseBundle\Listener;

use Dende\MultidatabaseBundle\Services\SubdomainProviderInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class SubdomainListener
{
    /**
     * @var SubdomainProviderInterface
     */
    private $subdomainProvider;

    /**
     * @var Container
     */
    private $container;

    /**
     * SubdomainNameListener constructor.
     * @param SubdomainProviderInterface $subdomainProvider
     * @param $container
     */
    public function __construct(SubdomainProviderInterface $subdomainProvider)
    {
        $this->subdomainProvider = $subdomainProvider;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $subdomainName = $this->subdomainProvider->getSubdomain();

//        $tenant = $this->container->get('doctrine.orm.default_entity_manager')
//            ->getRepository('Tenant')
//            ->findOneBySubdomain($subdomainName);
    }
}
