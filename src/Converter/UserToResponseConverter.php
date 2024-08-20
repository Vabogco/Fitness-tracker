<?php
declare(strict_types=1);

namespace App\Converter;

use App\DTO\UserResponse;
use App\Entity\User;

class UserToResponseConverter {

    public function convert(User $user): UserResponse {
        $userResponse = new UserResponse();
        $userResponse->setId($user->getId());
        $userResponse->setName($user->getName());

        return $userResponse;
    }
}
