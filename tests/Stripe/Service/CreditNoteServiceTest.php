<?php

declare(strict_types=1);

namespace Stripe\Service;

/**
 * @internal
 * @covers \Stripe\Service\CreditNoteService
 */
final class CreditNoteServiceTest extends \PHPUnit\Framework\TestCase
{
    use \Stripe\TestHelper;

    const TEST_RESOURCE_ID = 'cn_123';

    /** @var \Stripe\StripeClient */
    private $client;

    /** @var CreditNoteService */
    private $service;

    /**
     * @before
     */
    protected function setUpService(): void
    {
        $this->client = new \Stripe\StripeClient(['api_key' => 'sk_test_123', 'api_base' => MOCK_URL]);
        $this->service = new CreditNoteService($this->client);
    }

    public function testAll(): void
    {
        $this->expectsRequest(
            'get',
            '/v1/credit_notes'
        );
        $resources = $this->service->all();
        static::assertIsArray($resources->data);
        static::assertInstanceOf(\Stripe\CreditNote::class, $resources->data[0]);
    }

    public function testAllLines(): void
    {
        $this->expectsRequest(
            'get',
            '/v1/credit_notes/' . self::TEST_RESOURCE_ID . '/lines'
        );
        $resources = $this->service->allLines(self::TEST_RESOURCE_ID);
        static::assertIsArray($resources->data);
        static::assertInstanceOf(\Stripe\CreditNoteLineItem::class, $resources->data[0]);
    }

    public function testCreate(): void
    {
        $this->expectsRequest(
            'post',
            '/v1/credit_notes'
        );
        $resource = $this->service->create([
            'amount' => 100,
            'invoice' => 'in_132',
            'reason' => 'duplicate',
        ]);
        static::assertInstanceOf(\Stripe\CreditNote::class, $resource);
    }

    public function testPreview(): void
    {
        $this->expectsRequest(
            'get',
            '/v1/credit_notes/preview'
        );
        $resource = $this->service->preview([
            'amount' => 100,
            'invoice' => 'in_123',
        ]);
        static::assertInstanceOf(\Stripe\CreditNote::class, $resource);
    }

    public function testPreviewLines(): void
    {
        $this->expectsRequest(
            'get',
            '/v1/credit_notes/preview/lines'
        );
        $resources = $this->service->previewLines([
            'amount' => 100,
            'invoice' => 'in_123',
        ]);
        static::assertIsArray($resources->data);
        static::assertInstanceOf(\Stripe\CreditNoteLineItem::class, $resources->data[0]);
    }

    public function testRetrieve(): void
    {
        $this->expectsRequest(
            'get',
            '/v1/credit_notes/' . self::TEST_RESOURCE_ID
        );
        $resource = $this->service->retrieve(self::TEST_RESOURCE_ID);
        static::assertInstanceOf(\Stripe\CreditNote::class, $resource);
    }

    public function testUpdate(): void
    {
        $this->expectsRequest(
            'post',
            '/v1/credit_notes/' . self::TEST_RESOURCE_ID
        );
        $resource = $this->service->update(self::TEST_RESOURCE_ID, [
            'metadata' => ['key' => 'value'],
        ]);
        static::assertInstanceOf(\Stripe\CreditNote::class, $resource);
    }

    public function testVoidCreditNote(): void
    {
        $this->expectsRequest(
            'post',
            '/v1/credit_notes/' . self::TEST_RESOURCE_ID . '/void'
        );
        $resource = $this->service->voidCreditNote(self::TEST_RESOURCE_ID);
        static::assertInstanceOf(\Stripe\CreditNote::class, $resource);
    }
}
