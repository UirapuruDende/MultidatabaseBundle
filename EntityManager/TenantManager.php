<?php
namespace Dende\MultidatabaseBundle\EntityManager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TenantManager extends EntityManager
{
    public function __construct(EntityManager $em, EventDispatcherInterface $dispatcher)
    {
        $this->entityManager = $em;
        $this->dispatcher = $dispatcher;
    }
}

