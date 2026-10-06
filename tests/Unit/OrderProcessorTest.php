<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\NotificationServiceInterface;
use App\OrderProcessor;
use App\PricingServiceInterface;
use PHPUnit\Framework\TestCase;

/**
 * Exercise 04 - stubs and mocks.
 *
 * A stub only provides canned answers (state verification), a mock
 * additionally verifies how it is called (behaviour verification).
 */
final class OrderProcessorTest extends TestCase
{
    private const ORDER_ID = 1001;
    private const PRODUCT_ID = 42;

    public function testProcessOrderReturnsThePriceMultipliedByTheQuantity(): void
    {
        // Arrange: the pricing service is a stub that returns a canned price
        // for the ordered product
        $pricingService = $this->createStub(PricingServiceInterface::class);
        $pricingService->method('getPrice')->willReturnMap([
            [self::PRODUCT_ID, 19.99],
        ]);
        $notificationService = $this->createStub(NotificationServiceInterface::class);
        $orderProcessor = new OrderProcessor($pricingService, $notificationService);

        // Act
        $total = $orderProcessor->processOrder(self::ORDER_ID, self::PRODUCT_ID, 3);

        // Assert
        $this->assertEqualsWithDelta(59.97, $total, 0.00001);
    }

    public function testProcessOrderSendsExactlyOneConfirmationForTheOrder(): void
    {
        // Arrange: the notification service is a mock with an expectation
        $pricingService = $this->createStub(PricingServiceInterface::class);
        $pricingService->method('getPrice')->willReturn(19.99);

        $notificationService = $this->createMock(NotificationServiceInterface::class);
        $notificationService
            ->expects($this->once())
            ->method('sendOrderConfirmation')
            ->with(self::ORDER_ID);

        $orderProcessor = new OrderProcessor($pricingService, $notificationService);

        // Act
        $orderProcessor->processOrder(self::ORDER_ID, self::PRODUCT_ID, 3);

        // Assert: the mock verifies its expectation when the test ends
    }

    public function testProcessOrderReturnsZeroForAZeroQuantity(): void
    {
        // Arrange
        $pricingService = $this->createStub(PricingServiceInterface::class);
        $pricingService->method('getPrice')->willReturn(19.99);
        $notificationService = $this->createStub(NotificationServiceInterface::class);
        $orderProcessor = new OrderProcessor($pricingService, $notificationService);

        // Act
        $total = $orderProcessor->processOrder(self::ORDER_ID, self::PRODUCT_ID, 0);

        // Assert
        $this->assertSame(0.0, $total);
    }
}
