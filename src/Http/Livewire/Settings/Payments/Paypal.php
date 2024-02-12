<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Settings\Payments;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Shopper\Framework\Models\Shop\PaymentMethod;
use Shopper\Framework\Models\System\Currency;

class Paypal extends Component
{
    public string $paypal_key = '';

    public string $paypal_secret = '';

    public PaymentMethod|null $method = null;

    public bool $enabled = false;

    public string $message = '...';

    public function mount(): void
    {
        $this->enabled = ($this->method = PaymentMethod::where('slug', 'paypal')->first())
            ? $this->method->is_enabled
            : false;
        $this->paypal_key = env('PAYPAL_KEY', '');
        $this->paypal_secret = env('PAYPAL_SECRET', '');
    }

    public function enable(): void
    {
        $method = PaymentMethod::query()->firstOrCreate([
            'title' => 'Paypal',
            'slug' => 'paypal',
        ], [
            'title' => 'Paypal',
            'slug' => 'paypal',
            'link_url' => 'https://github.com/thephpleague/omnipay',
            'is_enabled' => true,
            'description' => 'Omnipay is a payment processing library for PHP. It has been designed based on ideas from Active Merchant, plus experience implementing dozens of gateways for [CI Merchant]. It has a clear and consistent API, is fully unit tested, and even comes with an example application to get you started.',
        ]);

        if (! $method->is_enabled) {
            $method->update([
                'is_enabled' => true,
            ]);
        }

        $this->enabled = true;

        Notification::make()
            ->title(__('shopper::layout.status.success'))
            ->body(__('shopper::pages/settings.notifications.paypal_enable'))
            ->success()
            ->send();
    }

    public function disable(): void
    {
        PaymentMethod::where('slug', 'paypal')->first()->update([
            'is_enabled' => false,
        ]);

        $this->enabled = false;

        Notification::make()
            ->title(__('shopper::layout.status.success'))
            ->body(__('shopper::pages/settings.notifications.paypal_disable'))
            ->success()
            ->send();
    }

    public function store(): void
    {
        Artisan::call('config:clear');

        setEnvironmentValue([
            'paypal_key' => $this->paypal_key,
            'paypal_secret' => $this->paypal_secret,
        ]);

        Notification::make()
            ->title(__('shopper::layout.status.updated'))
            ->body(__('shopper::pages/settings.notifications.paypal'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.settings.payments.paypal', [
            'currencies' => Cache::rememberForever('currencies', fn () => Currency::all()),
        ]);
    }
}
