<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Payments;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Shopper\Framework\Http\Livewire\AbstractBaseComponent;
use Shopper\Framework\Models\Shop\Payment;
use Shopper\Framework\Repositories\Ecommerce\PaymentRepository;

class Show extends AbstractBaseComponent
{
    public Model $payment;

    public int $payment_id;

    protected $listeners = [
        'trix:valueUpdated' => 'onTrixValueUpdate',
        'shopper:fileUpdated' => 'onFileUpdate',
    ];

    public function mount(Payment $payment)
    {
        $this->payment = $payment;
        $this->payment_id = $payment->id;
    }

    public function onTrixValueUpdate(string $value): void
    {
        $this->payment->payment_details = $value;
    }

    public function rules(): array
    {
        return [
            'payment.status' => 'required',
        ];
    }

    public function store(){}

    public function updateStatus()
    {
        $this->validate($this->rules());

        $this->payment->update([
            'status' => $this->payment->status,
        ]);

        $this->emit('paymentHasUpdated', $this->payment_id);

        Notification::make()
            ->title(__('shopper::layout.status.updated'))
            ->body(__('shopper::pages/payments.notifications.update'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.payments.show');
    }
}
