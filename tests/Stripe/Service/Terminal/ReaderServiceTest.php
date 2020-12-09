<?php

declare(strict_types=1);

namespace Stripe\Service\Terminal;

/**
 * @internal
 * @covers \Stripe\Service\Terminal\ReaderService
 */
final class ReaderServiceTest extends \PHPUnit\Framework\TestCase
{
    use \Stripe\TestHelper;

    const TEST_RESOURCE_ID = 'tml_123';

    /** @var \Stripe\StripeClient */
    private $client;

    /** @var ReaderService */
    private $service;

    /**
     * @before
     */
    protected function setUpService(): void
    {
        $this->client = new \Stripe\StripeClient(['api_key' => 'sk_test_123', 'api_base' => MOCK_URL]);
        $this->service = new ReaderService($this->client);
    }

    public function testAll(): void
    {
        $this->expectsRequest(
            'get',
            '/v1/terminal/readers'
        );
        $resources = $this->service->all();
        static::assertIsArray($resources->data);
        static::assertInstanceOf(\Stripe\Terminal\Reader::class, $resources->data[0]);
    }

    public function testCreate(): void
    {
        $this->expectsRequest(
            'post',
            '/v1/terminal/readers',
            ['registration_code' => 'a-b-c']
        );
        $resource = $this->service->create(['registration_code' => 'a-b-c']);
        static::assertInstanceOf(\Stripe\Terminal\Reader::class, $resource);
    }

    public function testDelete(): void
    {
        $this->expectsRequest(
            'delete',
            '/v1/terminal/readers/' . self::TEST_RESOURCE_ID
        );
        $resource = $this->service->delete(self::TEST_RESOURCE_ID);
        static::assertInstanceOf(\Stripe\Terminal\Reader::class, $resource);
    }

    public function testRetrieve(): void
    {
        $this->expectsRequest(
            'get',
            '/v1/terminal/readers/' . self::TEST_RESOURCE_ID
        );
        $resource = $this->service->retrieve(self::TEST_RESOURCE_ID);
        static::assertInstanceOf(\Stripe\Terminal\Reader::class, $resource);
    }

    public function testUpdate(): void
    {
        $this->expectsRequest(
            'post',
            '/v1/terminal/readers/' . self::TEST_RESOURCE_ID
        );
        $resource = $this->service->update(self::TEST_RESOURCE_ID, [
            'metadata' => ['key' => 'value'],
        ]);
        static::assertInstanceOf(\Stripe\Terminal\Reader::class, $resource);
    }
}
