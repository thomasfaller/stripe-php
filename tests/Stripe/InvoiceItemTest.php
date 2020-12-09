<?php

declare(strict_types=1);

namespace Stripe;

/**
 * @internal
 * @covers \Stripe\InvoiceItem
 */
final class InvoiceItemTest extends \PHPUnit\Framework\TestCase
{
    use TestHelper;

    const TEST_RESOURCE_ID = 'ii_123';

    public function testIsListable(): void
    {
        $this->expectsRequest(
            'get',
            '/v1/invoiceitems'
        );
        $resources = InvoiceItem::all();
        static::assertIsArray($resources->data);
        static::assertInstanceOf(\Stripe\InvoiceItem::class, $resources->data[0]);
    }

    public function testIsRetrievable(): void
    {
        $this->expectsRequest(
            'get',
            '/v1/invoiceitems/' . self::TEST_RESOURCE_ID
        );
        $resource = InvoiceItem::retrieve(self::TEST_RESOURCE_ID);
        static::assertInstanceOf(\Stripe\InvoiceItem::class, $resource);
    }

    public function testIsCreatable(): void
    {
        $this->expectsRequest(
            'post',
            '/v1/invoiceitems'
        );
        $resource = InvoiceItem::create([
            'amount' => 100,
            'currency' => 'usd',
            'customer' => 'cus_123',
        ]);
        static::assertInstanceOf(\Stripe\InvoiceItem::class, $resource);
    }

    public function testIsSaveable(): void
    {
        $resource = InvoiceItem::retrieve(self::TEST_RESOURCE_ID);
        $resource->metadata['key'] = 'value';
        $this->expectsRequest(
            'post',
            '/v1/invoiceitems/' . $resource->id
        );
        $resource->save();
        static::assertInstanceOf(\Stripe\InvoiceItem::class, $resource);
    }

    public function testIsUpdatable(): void
    {
        $this->expectsRequest(
            'post',
            '/v1/invoiceitems/' . self::TEST_RESOURCE_ID
        );
        $resource = InvoiceItem::update(self::TEST_RESOURCE_ID, [
            'metadata' => ['key' => 'value'],
        ]);
        static::assertInstanceOf(\Stripe\InvoiceItem::class, $resource);
    }

    public function testIsDeletable(): void
    {
        $invoiceItem = InvoiceItem::retrieve(self::TEST_RESOURCE_ID);
        $this->expectsRequest(
            'delete',
            '/v1/invoiceitems/' . $invoiceItem->id
        );
        $resource = $invoiceItem->delete();
        static::assertInstanceOf(\Stripe\InvoiceItem::class, $resource);
    }
}
