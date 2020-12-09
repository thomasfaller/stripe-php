<?php

declare(strict_types=1);

namespace Stripe;

/**
 * @internal
 * @covers \Stripe\OrderReturn
 */
final class OrderReturnTest extends \PHPUnit\Framework\TestCase
{
    use TestHelper;

    const TEST_RESOURCE_ID = 'orret_123';

    public function testIsListable(): void
    {
        $this->expectsRequest(
            'get',
            '/v1/order_returns'
        );
        $resources = OrderReturn::all();
        static::assertIsArray($resources->data);
        static::assertInstanceOf(\Stripe\OrderReturn::class, $resources->data[0]);
    }

    public function testIsRetrievable(): void
    {
        $this->expectsRequest(
            'get',
            '/v1/order_returns/' . self::TEST_RESOURCE_ID
        );
        $resource = OrderReturn::retrieve(self::TEST_RESOURCE_ID);
        static::assertInstanceOf(\Stripe\OrderReturn::class, $resource);
    }
}
