<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Activity;
use Doctrine\ORM\EntityManagerInterface;
use App\Enum\ActivityType;

class ActivityRepository {

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(Activity $activity): Activity {
       $this->entityManager->persist($activity);
       $this->entityManager->flush();

       return $activity;
    }

    public function findAll(): array 
    {
        $qb = $this->entityManager->createQueryBuilder()
            ->select('a')
            ->from('App\Entity\Activity', 'a');

        return $qb->getQuery()->getResult();
    }

    public function getActivitiesByType(ActivityType $activityType): array
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('a')
            ->from('App\Entity\Activity', 'a')
            ->where('a.activityType = :activityType')
            ->setParameter('activityType', $activityType->name);

        return $qb->getQuery()->getResult();
    }

    public function getDistanceByType(string $activityType): array
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('a.distanceUnit', 'SUM(a.distance) as totalDistance')
            ->from('App\Entity\Activity', 'a')
            ->where('a.activityType = :activityType')
            ->groupBy('a.distanceUnit')
            ->setParameter('activityType', $activityType);

        return $qb->getQuery()->getArrayResult();
    }

    public function getElapsedTimeByType(string $activityType): int
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('SUM(a.elapsedTime) as totalElapsedTime')
            ->from('App\Entity\Activity', 'a')
            ->where('a.activityType = :activityType')
            ->setParameter('activityType', $activityType);

        return $qb->getQuery()->getSingleScalarResult();
    }
}
