<?php
namespace Dende\MultidatabaseBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ConnectionCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $connection = $container
            ->getDefinition('doctrine.dbal.club_connection')
            ->addMethodCall('setCredentialsStorage', [new Reference('dende.multidatabase.credentials_storage')]);
    }
}
