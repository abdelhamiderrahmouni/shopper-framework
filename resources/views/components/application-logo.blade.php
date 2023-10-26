<img {{ $attributes->merge(['class' => 'dark:hidden']) }} src="{{ asset('shopper/images/shopper-logo.svg') }}" alt="{{ config('app.name') }}" />
<img {{ $attributes->merge(['class' => 'hidden dark:inline']) }} src="{{ asset('shopper/images/shopper-logo-white.svg') }}" alt="{{ config('app.name') }}" />
