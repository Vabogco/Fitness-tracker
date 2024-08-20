<?php
declare(strict_types=1);

namespace App\Factory;

use App\Entity\Activity;
use App\Entity\User;
use App\DTO\ActivityRequest;

class ActivityFactory
{
    public function requestToEntity(ActivityRequest $activityRequest, User $user): Activity
    {
       $activity = new Activity();
       $activity->setActivityType($activityRequest->getActivityType());
       $activity->setActivityDate($activityRequest->getActivityDate());
       $activity->setName($activityRequest->getName());
       $activity->setDistance($activityRequest->getDistance());
       $activity->setDistanceUnit($activityRequest->getDistanceUnit());
       $activity->setElapsedTime($activityRequest->getElapsedTime());
       $activity->setUser($user);

       return $activity;
    }
}
