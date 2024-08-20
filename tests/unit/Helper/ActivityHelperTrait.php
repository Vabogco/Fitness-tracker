<?php
declare(strict_types=1);

namespace App\Tests\Unit\Helper;

use App\Entity\Activity;
use App\Entity\User;
use App\Repository\ActivityRepository;
use App\Enum\ActivityType;
use App\Enum\DistanceUnit;

trait ActivityHelperTrait
{
    use UserHelperTrait;

    public function createActivity($client, User $user, ActivityType $activityType, DistanceUnit $distanceUnit): void
    {
        $activityRepository = $client->getContainer()
            ->get(ActivityRepository::class);

        $activity = new Activity();
        $activity->setActivityType($activityType);
        $activity->setActivityDate(new \Datetime('2024-08-24 22:15:23'));
        $activity->setName('Anna');
        $activity->setDistance(5);
        $activity->setDistanceUnit($distanceUnit);
        $activity->setElapsedTime(2250);
        $activity->setUser($user);
        $activityRepository->save($activity);
    }
}
