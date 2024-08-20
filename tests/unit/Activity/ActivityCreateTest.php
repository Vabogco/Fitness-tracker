<?php
declare(strict_types=1);

namespace Tests\Unit\Activity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\Unit\Helper\UserHelperTrait;
use App\Tests\Unit\Helper\DatabaseHelperTrait;

class ActivityCreateTest extends WebTestCase
{
    use UserHelperTrait;
    use DatabaseHelperTrait;

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

    /**
     * @dataProvider dataCreateActivitySuccess
     */
    public function testCreateActivitySuccess($requestData, $expectedData): void
    {
        $this->createUser($this->client, $expectedData['user_name']);

        $this->client->request(
            'POST',
            '/api/v1/activity',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($requestData)
        );

        $response = $this->client->getResponse();
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertEquals($expectedData, $responseData);
    }

    public function dataCreateActivitySuccess()
    {
        $requestData = [
            'user_id' => 1,
            'activity_type' => 'SWIMMING',
            'activity_date' => '2024-08-24 26:15:23',
            'name' => 'Evening swimming',
            'distance' => 18.143567,
            'distance_unit' => 'KM',
            'elapsed_time' => 2000,
        ];

        $expectedData = [
            'id' => 1,
            'activity_type' => 'SWIMMING',
            'activity_date' => '2024-08-25 02:15:23',
            'name' => 'Evening swimming',
            'distance' => 18.144,
            'distance_unit' => 'KM',
            'elapsed_time' => 2000,
            'user_id' => 1,
            'user_name' => 'Mike',
        ];

        $requestDataNoName = $requestData;
        $requestDataNoName['name'] = null;
        $expectedDataNoName = $expectedData;
        unset($expectedDataNoName['name']);

        yield [$requestData, $expectedData];
        yield [$requestDataNoName, $expectedDataNoName];
    }

    /**
     * @dataProvider dataCreateActivityError
     */
    public function testCreateActivityError($requestData, $expectedData): void
    {
        $this->createUser($this->client, 'Mike');

        $this->client->request(
            'POST',
            '/api/v1/activity',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($requestData)
        );

        $response = $this->client->getResponse();
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertEquals($expectedData, reset($responseData['errors'])['message']);
    }

    public function dataCreateActivityError(): iterable
    {
        $templateData = [
            'user_id' => 1,
            'activity_type' => 'SWIMMING',
            'activity_date' => '2024-08-24 22:15:23',
            'name' => 'Evening swimming',
            'distance' => 18.143567,
            'distance_unit' => 'KM',
            'elapsed_time' => 2000,
        ];

        $requestData = $templateData;
        $requestData['activity_type'] = null;
        $requestDataUserId = $templateData;
        $requestDataUserId['user_id'] = 55;
        $requestDataActivityDate = $templateData;
        $requestDataActivityDate['activity_date'] = '2024-08-24';
        $requestDataDistance = $templateData;
        $requestDataDistance['distance'] = '-18';
        $requestDataDistanceUnit = $templateData;
        $requestDataDistanceUnit['distance_unit'] = 'MII';
        $requestDataElapsedTime = $templateData;
        $requestDataElapsedTime['elapsed_time'] = 'time';

        $expectedData = 'Cannot assign null to property App\DTO\ActivityRequest::$activityType of type App\Enum\ActivityType';
        $expectedDataUserId = 'User with provided id does not exists';
        $expectedDataActivityDate = 'Invalid datetime "2024-08-24", expected one of the format "Y-m-d H:i:s".';
        $expectedDataDistance = 'This value should be positive.';
        $expectedDataDistanceUnit = '"MII" is not a valid backing value for enum App\\Enum\\DistanceUnit';
        $expectedDataElapsedTime = 'This value should be positive.';
        
        yield [$requestData, $expectedData];
        yield [$requestDataUserId, $expectedDataUserId];
        yield [$requestDataActivityDate, $expectedDataActivityDate];
        yield [$requestDataDistance, $expectedDataDistance];
        yield [$requestDataDistanceUnit, $expectedDataDistanceUnit];
        yield [$requestDataElapsedTime, $expectedDataElapsedTime];
    }
}
