<?php
declare(strict_types=1);

namespace App\Service;

use App\DTO\UserRequest;
use App\DTO\UserResponse;
use App\DTO\ErrorResponse;
use Psr\Log\LoggerInterface;
use App\Repository\UserRepository;
use App\Factory\UserFactory;
use App\Converter\UserToResponseConverter;
use App\Exception\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{
    private UserFactory $userFactory;
    private UserRepository $userRepository;
    private UserToResponseConverter $userToResponseConverter;
    private LoggerInterface $logger;
    private ValidatorInterface $validator;

    public function __construct(
        UserFactory $userFactory,
        UserRepository $userRepository,
        UserToResponseConverter $userToResponseConverter,
        LoggerInterface $logger,
        ValidatorInterface $validator,
    ) {
        $this->userFactory = $userFactory;
        $this->userRepository = $userRepository;
        $this->userToResponseConverter = $userToResponseConverter;
        $this->logger = $logger;
        $this->validator = $validator;
    }

    public function createUser(UserRequest $userRequest): UserResponse|ErrorResponse
    {
        $errors = $this->validator->validate($userRequest);

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $user = $this->userFactory->requestToEntity($userRequest);
        $user = $this->userRepository->save($user);

        return $this->userToResponseConverter->convert($user);
    }
}
