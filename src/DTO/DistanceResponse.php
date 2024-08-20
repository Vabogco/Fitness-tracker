<?php
declare(strict_types=1);

namespace App\DTO;

use App\Enum\DistanceUnit;
use OpenApi\Attributes as OA;
use JMS\Serializer\Annotation as Serializer;

class DistanceResponse
{
    #[OA\Property(example: 383.452)]
    #[Serializer\Type("float<3>")]
    private float $distance;

    private DistanceUnit $distanceUnit;

    public function __construct(DistanceUnit $distanceUnit, float $distance) {
        $this->distance = $distance;
        $this->distanceUnit = $distanceUnit;
    }

    public function getDistance(): float
    {
        return $this->distance;
    }

    public function getDistanceUnit(): DistanceUnit
    {
        return $this->distanceUnit;
    }
}
