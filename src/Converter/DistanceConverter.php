<?php
declare(strict_types=1);

namespace App\Converter;

use App\Enum\DistanceUnit;

class DistanceConverter {

    private const MILES_RATE = 1.60934;
    private const KM_RATE = 0.621371;

    public function convert(DistanceUnit $from, DistanceUnit $to, float $value): float {
        if ($from === $to) {
            return $value;
        }
        
        if ($from === DistanceUnit::MI) {
            return $this->milesToKilometers($value);
        }

        return $this->kilometersToMiles($value);
    }

    private function milesToKilometers(float $miles): float {
        return $miles * self::MILES_RATE;
    }

    private function kilometersToMiles(float $kilometers): float {
        return $kilometers * self::KM_RATE;
    }
}
