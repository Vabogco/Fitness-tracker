<?php
declare(strict_types=1);

namespace App\Tests\Unit\Activity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Service\ActivityService;
use App\DTO\ActivityResponse;
use App\Tests\Unit\Helper\DatabaseHelperTrait;
use App\Tests\Unit\Helper\ActivityHelperTrait;
use Symfony\Component\HttpFoundation\Response;
use App\Enum\ActivityType;
use App\Enum\DistanceUnit;

class ActivitiesGetTest extends WebTestCase
{
    use DatabaseHelperTrait;
    use ActivityHelperTrait;

    /** @var KernelBrowser */
    private $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();
        $this->setupDbSchemas($this->client);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->dropDatabase();
    }

    public function testGetActivitiesSuccess(): void
    {
        $mockService = $this->createMock(ActivityService::class);
        $mockService->expects($this->once())
            ->method('getActivities')
            ->willReturn([new ActivityResponse()]);

        $mockService->expects($this->never())
            ->method('getActivitiesByType');
            
        $container = self::getContainer();
        $container->set(ActivityService::class, $mockService);

        $this->client->request('GET', '/api/v1/activities');
        $response = $this->client->getResponse();
        $content = $response->getContent();
        $data = json_decode($content, true);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals(1, count($data));
    }

    public function testGetActivitiesByTypeSuccess(): void
    {
        $user = $this->createUser($this->client, 'Mike');
        $this->createActivity($this->client, $user, ActivityType::HIKING, DistanceUnit::KM);
        $this->createActivity($this->client, $user, ActivityType::SWIMMING, DistanceUnit::MI);

        $this->client->request('GET', '/api/v1/activities?activity_type=' . ActivityType::SWIMMING->name);
        $response = $this->client->getResponse();
        $content = $response->getContent();
        $data = json_decode($content, true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(ActivityType::SWIMMING->name, reset($data)['activity_type']);
        $this->assertEquals(1, count($data));
    }

    public function testGetActivitiesByTypeError(): void
    {
        $expectedData = '"HOKING_DOES_NOT_EXIST" is not a valid backing value for enum App\\Enum\\ActivityType';
        $this->client->request('GET', '/api/v1/activities?activity_type=HOKING_DOES_NOT_EXIST');
        $response = $this->client->getResponse();
        $content = $response->getContent();
        $data = json_decode($content, true);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals($expectedData, reset($data['errors'])['message']);
    }

    public function testGetActivitiesDistanceByTypeSuccess(): void
    {
        $expectedData = [
            // ML: 5 -> 8,04672
            // KM -> 5
            // lead -> 13.04672
            'distance' => 13.047,
            'distance_unit' => 'KM',
        ];
        $user = $this->createUser($this->client, 'Mike');
        $this->createActivity($this->client, $user, ActivityType::SWIMMING, DistanceUnit::KM);
        $this->createActivity($this->client, $user, ActivityType::SWIMMING, DistanceUnit::MI);
        $this->createActivity($this->client, $user, ActivityType::HIKING, DistanceUnit::KM);

        $this->client->request('GET', '/api/v1/activities/distance?activity_type=' . ActivityType::SWIMMING->name);
        $response = $this->client->getResponse();
        $content = $response->getContent();
        $data = json_decode($content, true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedData, $data);
    }

    public function testGetActivitiesElapsedTimeByTypeSuccess(): void
    {
        $expectedData = [
            'elapsed_time' => '01:15:00'
        ];
        $user = $this->createUser($this->client, 'Mike');
        $this->createActivity($this->client, $user, ActivityType::SWIMMING, DistanceUnit::KM);
        $this->createActivity($this->client, $user, ActivityType::SWIMMING, DistanceUnit::MI);
        $this->createActivity($this->client, $user, ActivityType::HIKING, DistanceUnit::KM);

        $this->client->request('GET', '/api/v1/activities/elapsed_time?activity_type=' . ActivityType::SWIMMING->name);
        $response = $this->client->getResponse();
        $content = $response->getContent();
        $data = json_decode($content, true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedData, $data);
    }
}
