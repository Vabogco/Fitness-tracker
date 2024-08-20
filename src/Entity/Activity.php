<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Enum\ActivityType;
use App\Enum\DistanceUnit;
use App\Entity\User;

#[ORM\Entity]
#[ORM\Table(name: 'activities')]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(name: 'activity_type', type: 'string', enumType: ActivityType::class)]
    private ActivityType $activityType;

    #[ORM\Column(name: 'activity_date', type: 'datetime', nullable: false)]
    private \DateTimeInterface $activityDate;

    #[ORM\Column(name: 'name', type: 'string', length: 255, nullable: true)]
    private ?string $name;

    #[ORM\Column(name: 'distance', type: 'decimal', precision: 10, scale: 3)]
    private float $distance;

    #[ORM\Column(name: 'distance_unit', type: 'string', enumType: DistanceUnit::class)]
    private DistanceUnit $distanceUnit;

    #[ORM\Column(name: 'elapsed_time', type: 'integer')]
    private int $elapsedTime;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'activities')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function getActivityType(): ActivityType
    {
        return $this->activityType;
    }

    public function setActivityType(ActivityType $activityType)
    {
        $this->activityType = $activityType;
    }

    public function getActivityDate(): \DateTimeInterface
    {
        return $this->activityDate;
    }

    public function setActivityDate(\DateTimeInterface $activityDate)
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user)
    {
        $this->user = $user;
    }
}
