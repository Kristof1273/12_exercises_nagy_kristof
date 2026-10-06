<?php

declare(strict_types=1);

namespace App;

/**
 * External service that knows the price of every product.
 */
interface PricingServiceInterface
{
    /**
     * Returns the unit price of the product with the given ID.
     */
    public function getPrice(int $productId): float;
}
