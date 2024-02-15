<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Controllers\Ecommerce;

use Illuminate\Contracts\View\View;
use Shopper\Framework\Http\Controllers\ShopperBaseController;
use Shopper\Framework\Models\Shop\Payment;

class PaymentController extends ShopperBaseController
{
    public function index(): View
    {
        $this->authorize('browse_payments');

        return view('shopper::pages.payments.index');
    }

    public function show(Payment $payment): View
    {
        $this->authorize('read_payments');

        return view('shopper::pages.payments.show', compact('payment'));
    }
}
