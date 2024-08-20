<?php
declare(strict_types=1);

namespace App\Controller;

use App\DTO\UserRequest;
use App\Service\UserService;
use JMS\Serializer\SerializerInterface as Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as FOSRest;

class UserController extends AbstractFOSRestController
{
    private UserService $userService;
    private Serializer $serializer;

    public function __construct(
        UserService $userService,
        Serializer $serializer,
    )
    {
        $this->userService = $userService;
        $this->serializer = $serializer;
    }
        
    #[FOSRest\Post('/api/v1/user')]
    public function createUser(Request $request): Response
    {
        $userRequest = $this->serializer->deserialize($request->getContent(), UserRequest::class, 'json');
        $user = $this->userService->createUser($userRequest);
        $user = $this->serializer->serialize($user, 'json');

        return new Response($user, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }
}
