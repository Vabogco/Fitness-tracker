<?php
declare(strict_types=1);

namespace App\DTO;

use OpenApi\Attributes as OA;
use JMS\Serializer\Annotation as Serializer;
use DateTime;

class ActivityResponse
{
    private int $id;

    #[OA\Property(example: 'running')]
    private string $activityType;

    #[OA\Property(example: '2024-08-24')]
    #[Serializer\Type("DateTime<'Y-m-d H:i:s'>")]
    private DateTime $activityDate;

    #[OA\Property(example: 'Evening Walk')]
    private ?string $name = null;

    #[OA\Property(example: 11.2)]
    #[Serializer\Type("float<3>")]
    private float $distance;

    #[OA\Property(example: 'MI')]
    private string $distanceUnit;

    #[OA\Property(example: 120)]
    private int $elapsedTime;

    private int $userId;
    private ?string $userName = null;

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getActivityType(): string
    {
        return $this->activityType;
    }

    public function setActivityType(string $activityType)
    {
        $this->activityType = $activityType;
    }

    public function getActivityDate(): DateTime
    {
        return $this->activityDate;
    }

    public function setActivityDate(DateTime $activityDate)
    {
        $this->activityDate = $activityDate;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name)
    {
        $this->name = $name;
    }

    public function getDistance(): float
    {
        return $this->distance;
    }

    public function setDistance(float $distance)
    {
        $this->distance = $distance;
    }

    public function getDistanceUnit(): string
    {
        return $this->distanceUnit;
    }

    public function setDistanceUnit(string $distanceUnit)
    {
        $this->distanceUnit = $distanceUnit;
    }

    public function getElapsedTime(): int
    {
        return $this->elapsedTime;
    }

    public function setElapsedTime(int $elapsedTime)
    {
        $this->elapsedTime = $elapsedTime;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId)
    {
        $this->userId = $userId;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(?string $userName)
    {
        $this->userName = $userName;
    }
}
