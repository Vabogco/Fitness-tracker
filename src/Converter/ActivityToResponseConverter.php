<?php
declare(strict_types=1);

namespace App\Converter;

use App\DTO\ActivityResponse;
use App\Entity\Activity;

class ActivityToResponseConverter {

    public function convert(Activity $activity): ActivityResponse {
        $activityResponse = new activityResponse();
        $activityResponse->setId($activity->getId());
        $activityResponse->setActivityType($activity->getActivityType()->name);
        $activityResponse->setActivityDate($activity->getActivityDate());
        $activityResponse->setName($activity->getName());
        $activityResponse->setDistance($activity->getDistance());
        $activityResponse->setDistanceUnit($activity->getDistanceUnit()->name);
        $activityResponse->setElapsedTime($activity->getElapsedTime());
        $activityResponse->setUserId($activity->getUser()->getId());
        $activityResponse->setUserName($activity->getUser()->getName());

        return $activityResponse;
    }
}
