<?php
namespace Dende\MultidatabaseBundle\Tests;

use Dende\MultidatabaseBundle\DataFixtures\ORM\CalendarsData;
use Dende\MultidatabaseBundle\DataFixtures\ORM\EventsData;
use Dende\MultidatabaseBundle\DataFixtures\ORM\OccurrencesData;
use Dende\CommonBundle\Tests\BaseFunctionalTest as BaseTest;

/**
 * Class BaseFunctionalTest
 * @package Dende\MultidatabaseBundle\Tests
 */
abstract class BaseFunctionalTest extends BaseTest
{
    public function setUp()
    {
        parent::setUp();
        $this->loadFixtures([ CalendarsData::class, EventsData::class, OccurrencesData::class], 'default');
    }
}
