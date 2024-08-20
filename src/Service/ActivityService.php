<?php
declare(strict_types=1);

namespace App\Service;

use App\Converter\ActivityToResponseConverter;
use App\DTO\ActivityRequest;
use App\DTO\ActivityResponse;
use App\DTO\ElapsedResponse;
use App\DTO\DistanceResponse;
use App\Converter\DistanceConverter;
use App\Factory\ActivityFactory;
use App\Repository\ActivityRepository;
use Psr\Log\LoggerInterface;
use App\Repository\UserRepository;
use App\Enum\ActivityType;
use App\Enum\DistanceUnit;
use App\Exception\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use DateTime;

class ActivityService
{
    private ActivityFactory $activityFactory;
    private ActivityRepository $activityRepository;
    private UserRepository $userRepository;
    private ActivityToResponseConverter $activityToResponseConverter;
    private DistanceConverter $distanceConverter;
    private LoggerInterface $logger;
    private ValidatorInterface $validator;

    public function __construct(
        ActivityFactory $activityFactory,
        ActivityRepository $activityRepository,
        UserRepository $userRepository,
        ActivityToResponseConverter $activityToResponseConverter,
        DistanceConverter $distanceConverter,
        LoggerInterface $logger,
        ValidatorInterface $validator,
    ) {
        $this->activityFactory = $activityFactory;
        $this->activityRepository = $activityRepository;
        $this->userRepository = $userRepository;
        $this->activityToResponseConverter = $activityToResponseConverter;
        $this->distanceConverter = $distanceConverter;
        $this->logger = $logger;
        $this->validator = $validator;
    }

    public function createActivity(ActivityRequest $activityRequest): ActivityResponse
    {
        $errors = $this->validator->validate($activityRequest);

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $user = $this->userRepository->findById($activityRequest->getUserId());
        $activity = $this->activityFactory->requestToEntity($activityRequest, $user);
        $activity = $this->activityRepository->save($activity);

        return $this->activityToResponseConverter->convert($activity);
    }

    public function getActivities(): array
    {
        $activities = $this->activityRepository->findAll();
        $activtiesResponse = [];

        foreach($activities as $activitiy) {
            $activtiesResponse[] = $this->activityToResponseConverter->convert($activitiy);
        }

        return $activtiesResponse;
    }

    public function getActivitiesByType(ActivityType $activityType): array
    {
        $activities = $this->activityRepository->getActivitiesByType($activityType);
        $activtiesResponse = [];

        foreach($activities as $activitiy) {
            $activtiesResponse[] = $this->activityToResponseConverter->convert($activitiy);
        }

        return $activtiesResponse;
    }

    public function getDistanceByType(string $activityType, DistanceUnit $distanceUnit): DistanceResponse
    {
        $distances = $this->activityRepository->getDistanceByType($activityType);
        $distancesSum = 0.0;

        foreach ($distances as $distance) {
            $distancesSum += $this->distanceConverter->convert(
                $distance['distanceUnit'],
                $distanceUnit,
                (float) $distance['totalDistance']
            );
        }

        return new DistanceResponse($distanceUnit, $distancesSum);
    }

    public function getElapsedTimeByType(string $activityType): ElapsedResponse
    {
        $elapsedTime = $this->activityRepository->getElapsedTimeByType($activityType);
        $elapsedResponse = new ElapsedResponse((new DateTime())->setTimestamp($elapsedTime));
        
        return $elapsedResponse;
    }
}
