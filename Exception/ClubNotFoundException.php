<?php
namespace Dende\MultidatabaseBundle\Exception;

/**
 * Class ClubNotFoundException
 * @package Dende\MultidatabaseBundle\Exception
 */
final class ClubNotFoundException extends \Exception
{
    /**
     * @param string $club
     */
    public function __construct($clubName)
    {
        $this->message = sprintf("Club '%s' not found.", $clubName);
    }
}
