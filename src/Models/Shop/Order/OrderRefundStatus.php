<?php

declare(strict_types=1);

namespace Shopper\Framework\Models\Shop\Order;

class OrderRefundStatus
{
    /**
     * Pending order refunds that have not been processed yet.
     */
    final public const PENDING = 'pending';

    /**
     * Order Refunds that are currently being treated.
     */
    final public const TREATMENT = 'treatment';

    /**
     * Order Payments that has been partially paid.
     */
    final public const PARTIAL_REFUND = 'partial_refund';

    /**
     * Order Payments that has been totally refunded.
     */
    final public const REFUNDED = 'refunded';

    /**
     * Order Payments that has been rejected.
     */
    final public const REJECTED = 'rejected';

    /**
     * Order that has been cancelled.
     */
    final public const CANCELLED = 'cancelled';

    public static function values(): array
    {
        return [
            self::PENDING => __('Pending'),
            self::TREATMENT => __('Treatment'),
            self::PARTIAL_REFUND => __('Partially refunded'),
            self::REFUNDED => __('Refunded'),
            self::REJECTED => __('Rejected'),
            self::CANCELLED => __('Cancelled'),
        ];
    }
}
