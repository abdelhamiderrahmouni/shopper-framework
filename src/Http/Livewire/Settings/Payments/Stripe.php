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

class Stripe extends Component
{
    public string $stripe_key = '';

    public string $stripe_secret = '';

    public bool $enabled = false;

    public string $message = '...';

    public function mount(): void
    {
        $this->enabled = ($stripe = PaymentMethod::where('slug', 'stripe')->first())
            ? $stripe->is_enabled
            : false;
        $this->stripe_key = env('STRIPE_KEY', '');
        $this->stripe_secret = env('STRIPE_SECRET', '');
    }

    public function enableStripe(): void
    {
        $method = PaymentMethod::query()->firstOrCreate([
            'title' => 'Stripe',
            'slug' => 'stripe',
        ],[
            'title' => 'Stripe',
            'slug' => 'stripe',
            'link_url' => 'https://github.com/stripe/stripe-php',
            'is_enabled' => true,
            'description' => 'The Stripe PHP library provides convenient access to the Stripe API from applications written in the PHP language.',
        ]);

        if (! $method->is_enabled) {
            $method->update([
                'is_enabled' => true,
            ]);
        }

        $this->enabled = true;

        Notification::make()
            ->title(__('shopper::layout.status.success'))
            ->body(__('shopper::pages/settings.notifications.stripe_enable'))
            ->success()
            ->send();
    }

    public function disableStripe(): void
    {
        PaymentMethod::where('slug', 'stripe')->first()->update([
            'is_enabled' => false,
        ]);

        $this->enabled = false;

        Notification::make()
            ->title(__('shopper::layout.status.success'))
            ->body(__('shopper::pages/settings.notifications.stripe_disable'))
            ->success()
            ->send();
    }

    public function store(): void
    {
        Artisan::call('config:clear');

        setEnvironmentValue([
            'stripe_key' => $this->stripe_key,
            'stripe_secret' => $this->stripe_secret,
        ]);

        Notification::make()
            ->title(__('shopper::layout.status.updated'))
            ->body(__('shopper::pages/settings.notifications.stripe'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.settings.payments.stripe', [
            'currencies' => Cache::rememberForever('currencies', fn () => Currency::all()),
        ]);
    }
}
