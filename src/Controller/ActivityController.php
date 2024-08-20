<?php
declare(strict_types=1);

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\ActivityService;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\DTO\ActivityRequest;
use App\DTO\ActivityResponse;
use App\DTO\DistanceResponse;
use App\DTO\ElapsedResponse;
use App\Enum\ActivityType;
use App\Enum\DistanceUnit;
use JMS\Serializer\SerializerInterface as Serializer;
use Psr\Log\LoggerInterface;

class ActivityController extends AbstractFOSRestController
{
    private ActivityService $activityService;
    private Serializer $serializer;
    private LoggerInterface $logger;

    public function __construct(ActivityService $activityService, Serializer $serializer, LoggerInterface $logger)
    {
        $this->activityService = $activityService;
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    #[FOSRest\Post('/api/v1/activity')]
    #[OA\Post(
        operationId: 'createActivity',
        requestBody: new OA\RequestBody(
            description: 'Input data format',
            content: new Model(type: ActivityRequest::class),
        ),
        tags: ['Activity'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Activity created',
                content: new Model(type: ActivityResponse::class),
            )
        ]
    )]
    public function createActivity(Request $request): Response
    {
        $activityRequest = $this->serializer->deserialize($request->getContent(), ActivityRequest::class, 'json');
        $activity = $this->activityService->createActivity($activityRequest);
        $activity = $this->serializer->serialize($activity, 'json');

        return new Response($activity, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    #[OA\Get(
        operationId: 'getActivity',
        tags: ['Activities'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'get activity',
                content: new Model(type: ActivityResponse::class),
            )
        ]
    )]
    #[OA\Parameter(
        name: 'activity_type',
        in: 'query',
        description: 'The field used to filter by activity type',
        schema: new OA\Schema(type: 'string')
    )]
    #[FOSRest\Get('/api/v1/activities')]
    public function getActivities(Request $request): Response
    {
        $activityType = $request->query->get('activity_type');

        if (isset($activityType)) {
            $activityType = ActivityType::from(strtoupper($activityType));
            $activities = $this->activityService->getActivitiesByType($activityType);
        } else {
            $activities = $this->activityService->getActivities();
        }
        
        $jsonActivities = $this->serializer->serialize($activities, 'json');

        return new Response($jsonActivities, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[OA\Get(
        operationId: 'getDistance',
        tags: ['Distance'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'get Ddstance',
                content: new Model(type: DistanceResponse::class),
            )
        ]
    )]
    #[OA\Parameter(
        name: 'activity_type',
        in: 'query',
        description: 'The field used to filter by activity type',
        schema: new OA\Schema(type: 'string')
    )]
    #[FOSRest\Get('/api/v1/activities/distance')]
    public function getDistanceByType(Request $request): Response
    {
        $activityType = $request->query->get('activity_type');
        $distanceUnit = DistanceUnit::KM;
        $unit = $request->query->get('unit');

        if (isset($unit)) {
            $distanceUnit = DistanceUnit::from(strtoupper(strtoupper($unit)));
        }

        $activityType = ActivityType::from($activityType);
        $distanceResponse = $this->activityService->getDistanceByType($activityType->name, $distanceUnit);
        $jsonDistanceResponse = $this->serializer->serialize($distanceResponse, 'json');

        return new Response($jsonDistanceResponse, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[OA\Get(
        operationId: 'getElapsedTime',
        tags: ['Distance'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'get Elapsed Time',
                content: new Model(type: ElapsedResponse::class),
            )
        ]
    )]
    #[OA\Parameter(
        name: 'activity_type',
        in: 'query',
        description: 'The field used to filter by activity type',
        schema: new OA\Schema(type: 'string')
    )]
    #[FOSRest\Get('/api/v1/activities/elapsed_time')]
    public function getElapsedTimeByType(Request $request): Response
    {
        $activityType = $request->query->get('activity_type');
        $activityType = ActivityType::from(strtoupper($activityType));
        $elapsedResponse = $this->activityService->getElapsedTimeByType($activityType->name);
        $jsonElapsedResponse = $this->serializer->serialize($elapsedResponse, 'json');

        return new Response($jsonElapsedResponse, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
