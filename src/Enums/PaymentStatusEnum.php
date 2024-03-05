<?php

namespace Shopper\Framework\Enums;

enum PaymentStatusEnum :string
{
    case PENDING = 'pending'; // The payment is awaiting further action, like manual verification or fraud review.

    case COMPLETE = 'complete'; // The payment has been successfully completed and the funds are transferred from the customer's account to yours

    case FAILED = 'failed'; // The payment attempt failed due to various reasons like network issues, technical errors, or invalid payment information.

    case CANCELLED = 'cancelled'; // The payment was cancelled by the customer or the payment gateway.

    case REFUNDED = 'refunded'; // You have issued a refund to the customer, reversing the payment.

    case APPROVED = 'approved'; // The payment has been approved and the funds are transferred from the customer's account to yours

    public static function toArray() :array
    {
        return [
            'pending' => 'pending',
            'complete' => 'complete',
            'failed' => 'failed',
            'cancelled' => 'cancelled',
            'refunded' => 'refunded',
        ];
    }
}
