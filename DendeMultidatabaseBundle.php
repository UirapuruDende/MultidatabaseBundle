<?php
namespace Dende\MultidatabaseBundle;

use Dende\MultidatabaseBundle\DependencyInjection\CompilerPass\ConnectionCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DendeMultidatabaseBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ConnectionCompilerPass());

        parent::build($container);
    }

    /**
     * Boots the Bundle.
     */
    public function boot()
    {
        parent::boot();

        if ($this->container->getParameter('kernel.environment') !== 'prod') {
            $this->container->get('dende.multidatabase.doctrine_fixtures_load_listener')->setOptions([
                'default' => $this->container->getParameter('standardfixtures'),
                'club'    => $this->container->getParameter('clubfixtures'),
            ]);
        }
    }
}
