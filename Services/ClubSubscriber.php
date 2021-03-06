<?php
namespace Dende\MultidatabaseBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Gyman\Bundle\ClubBundle\ClubsEvents;
use Gyman\Bundle\ClubBundle\Entity\Club;
use Gyman\Bundle\ClubBundle\Event\ClubCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ClubSubscriber implements EventSubscriberInterface
{
    /**
     * @var DatabaseWorker
     */
    private $databaseWorker;
    /**
     * @var ObjectManager
     */
    private $clubEntityManager;

    /**
     * @param ObjectManager $clubEntityManager
     */
    public function setClubEntityManager($clubEntityManager)
    {
        $this->clubEntityManager = $clubEntityManager;
    }

    /**
     * @param DatabaseWorker $databaseWorker
     */
    public function setDatabaseWorker($databaseWorker)
    {
        $this->databaseWorker = $databaseWorker;
    }

    public static function getSubscribedEvents()
    {
        return [
            ClubsEvents::CREATED => [['onClubCreated', 0]],
        ];
    }

    /**
     * creates database field from club name
     */
    public function onClubCreated(ClubCreatedEvent $event)
    {
        /**
         * @var Club $club
         */
        $club = $event->getClub();

        list($dbname, $user, $pass) = $this->databaseWorker->setupDatabase($club->getName());

        $club->setDatabase([
            CredentialsStorage::PARAM_BASE => $dbname,
            CredentialsStorage::PARAM_USER => $user,
            CredentialsStorage::PARAM_PASS => $pass,
        ]);

        $this->clubEntityManager->persist($club);
        $this->clubEntityManager->flush();
    }
}
