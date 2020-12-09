<?php

declare(strict_types=1);

namespace Stripe\Service;

/**
 * @internal
 * @covers \Stripe\Service\ProductService
 */
final class ProductServiceTest extends \PHPUnit\Framework\TestCase
{
    use \Stripe\TestHelper;

    const TEST_RESOURCE_ID = 'prod_123';

    /** @var \Stripe\StripeClient */
    private $client;

    /** @var ProductService */
    private $service;

    /**
     * @before
     */
    protected function setUpService(): void
    {
        $this->client = new \Stripe\StripeClient(['api_key' => 'sk_test_123', 'api_base' => MOCK_URL]);
        $this->service = new ProductService($this->client);
    }

    public function testAll(): void
    {
        $this->expectsRequest(
            'get',
            '/v1/products'
        );
        $resources = $this->service->all();
        static::assertIsArray($resources->data);
        static::assertInstanceOf(\Stripe\Product::class, $resources->data[0]);
    }

    public function testCreate(): void
    {
        $this->expectsRequest(
            'post',
            '/v1/products'
        );
        $resource = $this->service->create([
            'name' => 'name',
        ]);
        static::assertInstanceOf(\Stripe\Product::class, $resource);
    }

    public function testDelete(): void
    {
        $this->expectsRequest(
            'delete',
            '/v1/products/' . self::TEST_RESOURCE_ID
        );
        $resource = $this->service->delete(self::TEST_RESOURCE_ID);
        static::assertInstanceOf(\Stripe\Product::class, $resource);
    }

    public function testRetrieve(): void
    {
        $this->expectsRequest(
            'get',
            '/v1/products/' . self::TEST_RESOURCE_ID
        );
        $resource = $this->service->retrieve(self::TEST_RESOURCE_ID);
        static::assertInstanceOf(\Stripe\Product::class, $resource);
    }

    public function testUpdate(): void
    {
        $this->expectsRequest(
            'post',
            '/v1/products/' . self::TEST_RESOURCE_ID
        );
        $resource = $this->service->update(self::TEST_RESOURCE_ID, [
            'metadata' => ['key' => 'value'],
        ]);
        static::assertInstanceOf(\Stripe\Product::class, $resource);
    }
}
