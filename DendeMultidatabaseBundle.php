<?php
namespace Dende\MultidatabaseBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class DendeMultidatabaseBundle extends Bundle
{
//    /**
//     * Boots the Bundle.
//     */
//    public function boot()
//    {
//        parent::boot();
//
//        if ($this->container->getParameter('kernel.environment') !== 'prod') {
//            $this->container->get('dende.multidatabase.doctrine_fixtures_load_listener')->setOptions([
//                'default' => $this->container->getParameter('standardfixtures'),
//                'tenant'    => $this->container->getParameter('tenantfixtures'),
//            ]);
//        }
//    }
}
