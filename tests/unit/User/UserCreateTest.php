<?php
declare(strict_types=1);

namespace Tests\Unit\User;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\Unit\Helper\DatabaseHelperTrait;
use Symfony\Component\HttpFoundation\Response;

class UserCreateTest extends WebTestCase
{
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

    public function testCreateUserSuccess(): void
    {
        $requestData = [
            'name' => 'Mike',
        ];

        $expectedData = [
            'id' => 1,
            'name' => 'Mike',
        ];

        $this->client->request(
            'POST',
            '/api/v1/user',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($requestData)
        );

        $response = $this->client->getResponse();
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($expectedData, $responseData);
    }

    public function testCreateUserError(): void
    {
        $requestData = [
            'name' => null,
        ];

        $expectedData = [
            'message' => 'Request contains invalid data types.',
            'errors' => [
                [
                    'message' => 'Cannot assign null to property App\\DTO\\UserRequest::$name of type string'
                ]
            ],
        ];

        $this->client->request(
            'POST',
            '/api/v1/user',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($requestData)
        );

        $response = $this->client->getResponse();
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals($expectedData, $responseData);
    }
}
