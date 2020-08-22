<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\UserLambda;
use Symfony\Contracts\EventDispatcher\Event;

class UserLambdaRegisterEvent extends Event
{
    private UserLambda $userLambda;

    public function __construct(UserLambda $userLambda)
    {
        $this->userLambda = $userLambda;
    }
    public function getUserLambda(): UserLambda
    {
        return $this->userLambda;
    }
}
