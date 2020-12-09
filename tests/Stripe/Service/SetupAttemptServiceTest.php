<?php

declare(strict_types=1);

namespace Stripe\Service;

/**
 * @internal
 * @covers \Stripe\Service\SetupAttemptService
 */
final class SetupAttemptServiceTest extends \PHPUnit\Framework\TestCase
{
    use \Stripe\TestHelper;

    /** @var \Stripe\StripeClient */
    private $client;

    /** @var SetupAttemptService */
    private $service;

    /**
     * @before
     */
    protected function setUpService(): void
    {
        $this->client = new \Stripe\StripeClient(['api_key' => 'sk_test_123', 'api_base' => MOCK_URL]);
        $this->service = new SetupAttemptService($this->client);
    }

    public function testAll(): void
    {
        $this->expectsRequest(
            'get',
            '/v1/setup_attempts'
        );
        $resources = $this->service->all([
            'setup_intent' => 'si_123',
        ]);
        static::assertIsArray($resources->data);
        static::assertInstanceOf(\Stripe\SetupAttempt::class, $resources->data[0]);
    }
}
