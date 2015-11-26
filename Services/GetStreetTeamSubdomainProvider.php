<?php
namespace Dende\MultidatabaseBundle\Services;

use Symfony\Component\HttpFoundation\RequestStack;

final class GetStreetTeamSubdomainProvider implements SubdomainProviderInterface
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var
     */
    private $baseUrl;

    /**
     * SubdomainProvider constructor.
     * @param RequestStack $requestStack
     * @param $baseUrl
     */
    public function __construct(RequestStack $requestStack, $baseUrl)
    {
        $this->requestStack = $requestStack;
        $this->baseUrl = $baseUrl;
    }

    /**
     * @return string|null
     */
    public function getSubdomain()
    {
        $currentHost = $this->requestStack->getCurrentRequest()->getHttpHost();

        $subdomain = explode(".", $currentHost)[0];

        return $subdomain;
    }
}
