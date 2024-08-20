<?php
declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Attributes as OA;

class UserRequest
{
    private int $id;

    #[OA\Property(example: 'John Dohe')]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $name;

    public function getId(int $id)
    {
        $this->id = $id;
    }

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
