@if (filled($brand = config('shopper.system.brand')))
    <img {{ $attributes }} src="{{ asset($brand) }}" alt="{{ config('app.name') }}" />
@else
    <img {{ $attributes }} src="{{ asset('shopper/images/shopper-icon.svg') }}" alt="{{ config('app.name') }}" />
@endif
