<?php
declare(strict_types=1);

namespace App\DTO;

use OpenApi\Attributes as OA;

class UserResponse
{
    #[OA\Property(example: 9)]
    private int $id;

    #[OA\Property(example: 'Emily')]
    private string $name;

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }
}
