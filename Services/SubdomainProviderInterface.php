<?php
namespace Dende\MultidatabaseBundle\Services;

interface SubdomainProviderInterface
{
    /**
     * @return string
     */
    public function getSubdomain();
}