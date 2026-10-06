<?php

declare(strict_types=1);

namespace App;

/**
 * External service that notifies the customer about their order.
 */
interface NotificationServiceInterface
{
    /**
     * Sends a confirmation for the order with the given ID.
     */
    public function sendOrderConfirmation(int $orderId): void;
}
