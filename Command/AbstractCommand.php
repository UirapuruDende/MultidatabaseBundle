<?php
namespace Dende\MultidatabaseBundle\Command;

use Dende\MultidatabaseBundle\Services\DatabaseWorker;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractCommand extends ContainerAwareCommand
{
    /**
     * @var ContainerInterface|null
     */
    protected $container;

    /**
     * @var DatabaseWorker
     */
    protected $dataBaseWorker;

    /**
     * @param DatabaseWorker $dataBaseWorker
     */
    public function setDataBaseWorker($dataBaseWorker)
    {
        $this->dataBaseWorker = $dataBaseWorker;
    }
}
