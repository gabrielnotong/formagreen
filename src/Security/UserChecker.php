<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{

    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof User) {
            return;
        }

        if (!$user->isActive()) {
            // the message is displayed to the user interface
            throw new CustomUserMessageAuthenticationException('Your account has been disabled');
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
    }
}
