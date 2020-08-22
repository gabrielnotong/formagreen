<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\TrainingCenter;
use Symfony\Contracts\EventDispatcher\Event;

class TrainingCenterRegisterEvent extends Event
{
    private TrainingCenter $trainingCenter;

    public function __construct(TrainingCenter $trainingCenter)
    {
        $this->trainingCenter = $trainingCenter;
    }
    public function getTrainingCenter(): TrainingCenter
    {
        return $this->trainingCenter;
    }
}
