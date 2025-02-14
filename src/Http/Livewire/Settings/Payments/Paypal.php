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
    public string $paypal_client_id = '';

    public string $paypal_client_secret = '';

    public string $paypal_currency = 'USD';

    public float $paypal_currency_conversion_rate = 1.0;

    public bool $paypal_test_mode = true;

    public array $paypal_currencies = [
        'AUD' => 'Australian dollar',
        'BRL' => 'Brazilian real 2',
        'CAD' => 'Canadian dollar',
        'CNY' => 'Chinese Renmenbi 4',
        'CZK' => 'Czech koruna',
        'DKK' => 'Danish krone',
        'EUR' => 'Euro',
        'HKD' => 'Hong Kong dollar',
        'HUF' => 'Hungarian forint 1',
        'ILS' => 'Israeli new shekel',
        'JPY' => 'Japanese yen 1',
        'MYR' => 'Malaysian ringgit 3',
        'MXN' => 'Mexican peso',
        'TWD' => 'New Taiwan dollar 1',
        'NZD' => 'New Zealand dollar',
        'NOK' => 'Norwegian krone',
        'PHP' => 'Philippine peso',
        'PLN' => 'Polish złoty',
        'GBP' => 'Pound sterling',
        'SGD' => 'Singapore dollar',
        'SEK' => 'Swedish krona',
        'CHF' => 'Swiss franc',
        'THB' => 'Thai baht',
        'USD' => 'United States dollar',
    ];

    public ?PaymentMethod $method = null;

    public bool $enabled = false;

    public string $message = '...';

    public function mount(): void
    {
        $this->enabled = ($this->method = PaymentMethod::where('slug', 'paypal')->first())
            ? $this->method->is_enabled
            : false;
        $this->paypal_client_id = env('PAYPAL_CLIENT_ID', '');
        $this->paypal_client_secret = env('PAYPAL_CLIENT_SECRET', '');
        $this->paypal_currency = env('PAYPAL_CURRENCY', 'USD');
        $this->paypal_currency_conversion_rate = (float) env('PAYPAL_CURRENCY_CONVERSION_RATE', 1.0);
        $this->paypal_test_mode = env('PAYPAL_TEST_MODE', true);
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
            'PAYPAL_CLIENT_ID' => $this->paypal_client_id,
            'PAYPAL_CLIENT_SECRET' => $this->paypal_client_secret,
            'PAYPAL_CURRENCY' => $this->paypal_currency,
            'PAYPAL_CURRENCY_CONVERSION_RATE' => $this->paypal_currency_conversion_rate,
            'PAYPAL_TEST_MODE' => $this->paypal_test_mode,
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
