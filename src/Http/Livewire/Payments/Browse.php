<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Payments;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Framework\Repositories\Ecommerce\PaymentRepository;

class Browse extends Component
{
    public function render(): View
    {
        return view('shopper::livewire.brands.browse', [
            'total' => (new PaymentRepository())->count(),
        ]);
    }
}
