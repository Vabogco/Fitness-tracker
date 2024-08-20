<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Exception\UserNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

class UserRepository {

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findById(int $id): User 
    {
        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(array('id' => $id));

        if (!$user instanceof User) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    public function save(User $user): User {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
 
        return $user;
     }
}
