<?php
declare(strict_types=1);

namespace App\Factory;

use App\Entity\User;
use App\DTO\UserRequest;

class UserFactory
{
    public function requestToEntity(UserRequest $userRequest): User
    {
       $user = new User();
       $user->setName($userRequest->getName());

       return $user;
    }
}
