<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\GreenSpace;
use Symfony\Contracts\EventDispatcher\Event;

class GreenSpaceCreatedEvent extends Event
{
    private GreenSpace $greenSpace;

    public function __construct(GreenSpace $greenSpace)
    {
        $this->greenSpace = $greenSpace;
    }
    public function getGreenSpace(): GreenSpace
    {
        return $this->greenSpace;
    }
}
