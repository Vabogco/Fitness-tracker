<?php
declare(strict_types=1);

namespace App\EventListener;

use JMS\Serializer\Exception\RuntimeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use App\Exception\ValidationException;
use App\Exception\UserNotFoundException;
use Psr\Log\LoggerInterface;

class ExceptionListener
{
    private LoggerInterface $logger;

    public function __construct(
        LoggerInterface $logger,
    ) {
        $this->logger = $logger;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ValidationException) {
            $errorsFormated = $exception->formatErrors();
            $response = new JsonResponse(
                [
                    'message' => 'Parameters mismatch.',
                    'errors' => $errorsFormated,
                ],
                JsonResponse::HTTP_BAD_REQUEST,
            );

            $this->logger->error('Validation error while saving activities.', ['errors' => $errorsFormated]);

            $event->setResponse($response);
        }

        if ($exception instanceof UserNotFoundException) {
            $response = new JsonResponse(
                [
                    'message' => 'Parameters mismatch.',
                    'errors' => [[
                        'message' => 'User with provided id does not exists',
                        'tag' => 'user_id',
                    ]],
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );

            $this->logger->error($exception->getMessage());

            $event->setResponse($response);
        }

        if ($exception instanceof \Error) {
            $response = new JsonResponse(
                [
                    'message' => 'Request contains invalid data types.',
                    'errors' => [[
                        'message' => $exception->getMessage(),
                    ]],
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );

            $this->logger->error($exception->getMessage());

            $event->setResponse($response);
        }

        if ($exception instanceof RuntimeException) {
            $response = new JsonResponse(
                [
                    'message' => 'The request could not be processed due to error.',
                    'errors' => [[
                        'message' => $exception->getMessage(),
                    ]],
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );

            $this->logger->error($exception->getMessage());

            $event->setResponse($response);
        }
    }
}
