<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;

/**
 * Processes orders. Both collaborators are injected through the
 * constructor, so they can be replaced with test doubles.
 */
final class OrderProcessor
{
    public function __construct(
        private readonly PricingServiceInterface $pricingService,
        private readonly NotificationServiceInterface $notificationService,
    ) {
    }

    /**
     * Looks up the price of the product, calculates the order total
     * (price x quantity), sends an order confirmation and returns the total.
     *
     * @throws InvalidArgumentException when the quantity is negative
     */
    public function processOrder(int $orderId, int $productId, int $quantity): float
    {
        if ($quantity < 0) {
            throw new InvalidArgumentException(
                sprintf('The quantity must not be negative, %d given.', $quantity),
            );
        }

        $price = $this->pricingService->getPrice($productId);
        $total = $price * $quantity;

        $this->notificationService->sendOrderConfirmation($orderId);

        return $total;
    }
}
