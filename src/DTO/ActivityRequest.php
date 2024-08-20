<?php
declare(strict_types=1);

namespace App\DTO;

use App\Enum\ActivityType;
use App\Enum\DistanceUnit;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Attributes as OA;
use JMS\Serializer\Annotation as Serializer;

class ActivityRequest
{
    #[Assert\NotBlank]
    #[OA\Property(example: 'running')]
    #[Serializer\SerializedName("activity_type")]
    #[Serializer\Type("App\Enum\ActivityType")]
    private ActivityType $activityType;

    #[Assert\Type(\DateTimeInterface::class)]
    #[OA\Property(example: '2024-08-24')]
    #[Serializer\SerializedName("activity_date")]
    #[Serializer\Type("DateTime<'Y-m-d H:i:s'>")]
    private DateTime $activityDate;

    #[Assert\Length(max: 255)]
    #[OA\Property(example: 'Evening running')]
    #[Serializer\Type("string")]
    private ?string $name = null;

    #[Assert\Positive]
    #[OA\Property(example: 143.567)]
    #[Serializer\Type("float<3>")]
    private float $distance;

    #[Assert\NotBlank]
    #[OA\Property(example: 'KM')]
    #[Serializer\SerializedName("distance_unit")]
    #[Serializer\Type("App\Enum\DistanceUnit")]
    private DistanceUnit $distanceUnit;

    #[Assert\Positive]
    #[OA\Property(example: '3000')]
    #[Serializer\SerializedName("elapsed_time")]
    #[Serializer\Type("int")]
    private int $elapsedTime;

    #[Assert\Positive]
    #[OA\Property(example: '15')]
    #[Serializer\SerializedName("user_id")]
    #[Serializer\Type("int")]
    private int $userId;

    public function getActivityType(): ActivityType
    {
        return $this->activityType;
    }

    public function setActivityType(ActivityType $activityType)
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

    public function getDistanceUnit(): DistanceUnit
    {
        return $this->distanceUnit;
    }

    public function setDistanceUnit(DistanceUnit $distanceUnit)
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
}
