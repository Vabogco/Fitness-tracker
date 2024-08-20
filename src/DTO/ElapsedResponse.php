<?php
declare(strict_types=1);

namespace App\DTO;

use DateTime;
use OpenApi\Attributes as OA;
use JMS\Serializer\Annotation as Serializer;

class ElapsedResponse
{
    #[OA\Property(example: '05:33:20')]
    #[Serializer\Type("DateTime<'H:i:s','UTC'>")]
    private DateTime $elapsedTime;

    public function __construct(DateTime $elapsedTime) {
        $this->elapsedTime = $elapsedTime;
    }

    public function getElapsedTime(): DateTime
    {
        return $this->elapsedTime;
    }
}
