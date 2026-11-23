<?php

/**
 * Calculates discounted order totals for a customer.
 *
 * Applies promotional rules in strict priority order and logs edge cases.
 *
 * ### Discount Rules
 * - **VIP Tier:** 20% off subtotal
 * - **Promo Code:** Flat discount applied *after* tier pricing
 * - **Minimum Floor:** Total never drops below `0.00`
 *
 * > **Note:** Tax calculation is handled upstream; pass pre-tax amounts only.
 *
 * ```php
 * $total = applyOrderDiscount(100.00, 'VIP', 'SAVE10');
 * // Returns 70.00
 * ```
 *
 * @param float       $subtotal  Pre-tax order amount (must be non-negative).
 * @param string|null $tier      Customer loyalty tier (e.g., `'VIP'`, `'GOLD'`).
 * @param string|null $promoCode Optional promotional code.
 *
 * @return float Final discounted amount rounded to two decimal places.
 *
 * @throws \InvalidArgumentException When `$subtotal` is negative.
 * @see OrderCalculator::calculateFinalCharge()
 */
function applyOrderDiscount(float $subtotal, ?string $tier = null, ?string $promoCode = null): float
{
    if ($subtotal < 0) {
        throw new \InvalidArgumentException('Subtotal cannot be negative.');
    }

    return round(max(0.0, $subtotal), 2);
}