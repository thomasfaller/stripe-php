<?php

declare(strict_types=1);

namespace Stripe;

/**
 * @internal
 * @covers \Stripe\Source
 */
final class SourceTest extends \PHPUnit\Framework\TestCase
{
    use TestHelper;

    const TEST_RESOURCE_ID = 'src_123';

    public function testIsRetrievable(): void
    {
        $this->expectsRequest(
            'get',
            '/v1/sources/' . self::TEST_RESOURCE_ID
        );
        $resource = Source::retrieve(self::TEST_RESOURCE_ID);
        static::assertInstanceOf(\Stripe\Source::class, $resource);
    }

    public function testIsCreatable(): void
    {
        $this->expectsRequest(
            'post',
            '/v1/sources'
        );
        $resource = Source::create([
            'type' => 'card',
        ]);
        static::assertInstanceOf(\Stripe\Source::class, $resource);
    }

    public function testIsSaveable(): void
    {
        $resource = Source::retrieve(self::TEST_RESOURCE_ID);
        $resource->metadata['key'] = 'value';
        $this->expectsRequest(
            'post',
            '/v1/sources/' . $resource->id
        );
        $resource->save();
        static::assertInstanceOf(\Stripe\Source::class, $resource);
    }

    public function testIsUpdatable(): void
    {
        $this->expectsRequest(
            'post',
            '/v1/sources/' . self::TEST_RESOURCE_ID
        );
        $resource = Source::update(self::TEST_RESOURCE_ID, [
            'metadata' => ['key' => 'value'],
        ]);
        static::assertInstanceOf(\Stripe\Source::class, $resource);
    }

    public function testCanSaveCardExpiryDate(): void
    {
        $response = [
            'id' => 'src_foo',
            'object' => 'source',
            'card' => [
                'exp_month' => 8,
                'exp_year' => 2019,
            ],
        ];
        $source = Source::constructFrom($response);

        $response['card']['exp_month'] = 12;
        $response['card']['exp_year'] = 2022;
        $this->stubRequest(
            'POST',
            '/v1/sources/src_foo',
            [
                'card' => [
                    'exp_month' => 12,
                    'exp_year' => 2022,
                ],
            ],
            null,
            false,
            $response
        );

        $source->card->exp_month = 12;
        $source->card->exp_year = 2022;
        $source->save();

        static::assertSame(12, $source->card->exp_month);
        static::assertSame(2022, $source->card->exp_year);
    }

    public function testIsDetachableWhenAttached(): void
    {
        $resource = Source::retrieve(self::TEST_RESOURCE_ID);
        $resource->customer = 'cus_123';
        $this->expectsRequest(
            'delete',
            '/v1/customers/cus_123/sources/' . $resource->id
        );
        $resource->detach();
        static::assertInstanceOf(\Stripe\Source::class, $resource);
    }

    public function testIsNotDetachableWhenUnattached(): void
    {
        $this->expectException(\Stripe\Exception\UnexpectedValueException::class);

        $resource = Source::retrieve(self::TEST_RESOURCE_ID);
        $resource->detach();
    }

    public function testCanListSourceTransactionsDeprecated(): void
    {
        $source = Source::retrieve(self::TEST_RESOURCE_ID);
        $this->expectsRequest(
            'get',
            '/v1/sources/' . $source->id . '/source_transactions'
        );
        $resources = $source->sourceTransactions();
        static::assertIsArray($resources->data);
        static::assertInstanceOf(\Stripe\SourceTransaction::class, $resources->data[0]);
    }

    public function testCanListSourceTransactions(): void
    {
        $this->expectsRequest(
            'get',
            '/v1/sources/' . self::TEST_RESOURCE_ID . '/source_transactions'
        );
        $resources = Source::allSourceTransactions(self::TEST_RESOURCE_ID);
        static::assertIsArray($resources->data);
        static::assertInstanceOf(\Stripe\SourceTransaction::class, $resources->data[0]);
    }

    public function testCanVerify(): void
    {
        $resource = Source::retrieve(self::TEST_RESOURCE_ID);
        $this->expectsRequest(
            'post',
            '/v1/sources/' . $resource->id . '/verify'
        );
        $resource->verify(['values' => [32, 45]]);
        static::assertInstanceOf(\Stripe\Source::class, $resource);
    }
}
