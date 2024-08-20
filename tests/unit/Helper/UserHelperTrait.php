<?php
declare(strict_types=1);

namespace App\Tests\Unit\Helper;

use App\Repository\UserRepository;
use App\Entity\User;

trait UserHelperTrait
{
    public function createUser($client, $userName): User
    {
        $userRepository = $client->getContainer()
            ->get(UserRepository::class);

        $user = new User();
        $user->setName($userName);
        $userRepository->save($user);

        return $user;
    }
}
