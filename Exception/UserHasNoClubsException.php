<?php
namespace Dende\MultidatabaseBundle\Exception;

use Gyman\Bundle\UserBundle\Entity\User;

/**
 * Class UserHasNoClubsException
 * @package Dende\MultidatabaseBundle\Exception
 */
class UserHasNoClubsException extends \Exception
{
    public function __construct(User $user)
    {
        $this->message = sprintf("User '%s' has no clubs assigned!", $user->getEmail());
    }
}
