<div>
    <x-shopper::breadcrumb :back="route('shopper.payments.index')">
        <x-heroicon-s-chevron-left class="shrink-0 h-5 w-5 text-secondary-400 dark:text-secondary-500" />
        <x-shopper::breadcrumb.link :link="route('shopper.payments.index')" :title="__('shopper::layout.sidebar.payments')" />
    </x-shopper::breadcrumb>

    <x-shopper::heading class="mt-3">
        <x-slot name="title">
            {{ strtolower(__('shopper::words.payment')) }}: #{{ $payment->transaction_id }}
        </x-slot>

        <x-slot name="action">
            <x-shopper::buttons.primary wire:click="store" wire.loading.attr="disabled" type="button">
                <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                {{ __('shopper::layout.forms.actions.save') }}
            </x-shopper::buttons.primary>
        </x-slot>
    </x-shopper::heading>

    <div class="mt-6 space-y-5 lg:space-y-0 lg:grid lg:grid-cols-6 lg:gap-6">
        <div class="lg:col-span-4 space-y-5">
            <div class="bg-white dark:bg-secondary-800 rounded-lg shadow p-4 sm:p-5">
                <div>
                    <x-shopper::forms.group
                        for="name"
                        isRequired
                        :label="__('shopper::layout.forms.label.name')"
                    >
                        <x-shopper::forms.input
                            wire:model.defer="payment.user.full_name"
                            id="name"
                            type="text"
                            autocomplete="off"
                            disabled
                            placeholder="Apple, Nike, Samsung..."
                        />
                    </x-shopper::forms.group>
                </div>
                <div class="mt-4">
                    kkk
                </div>
                <div class="mt-5">
                    <x-shopper::forms.group :label="__('shopper::layout.forms.label.payment_details')" for="payment_details">
                        <livewire:shopper-forms.trix :value="$payment->payment_details" />
                    </x-shopper::forms.group>
                </div>
            </div>
        </div>
        <div class="lg:col-span-2">
            <aside class="sticky top-6 space-y-5">
                <div class="bg-white dark:bg-secondary-800 rounded-md shadow overflow-hidden divide-y divide-secondary-200 dark:divide-secondary-700">
                    <div class="p-4 sm:p-5">
                        <x-shopper::label :value="__('shopper::layout.forms.label.order')" />
                        <div class="mt-1">
                            <x-shopper::forms.group
                                for="name"
                                isRequired
                                :label="__('shopper::layout.forms.label.order')"
                            >
                                <x-shopper::forms.input
                                    wire:model.defer="payment.order.number"
                                    id="name"
                                    type="text"
                                    autocomplete="off"
                                    disabled
                                    placeholder="Apple, Nike, Samsung..."
                                />
                            </x-shopper::forms.group>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-secondary-800 rounded-md shadow overflow-hidden divide-y divide-secondary-200 dark:divide-secondary-700">
                    <div class="p-4 sm:p-5">
                        <x-shopper::label :value="__('shopper::layout.forms.label.amount')" />
                        <div class="mt-1">
                            <x-shopper::forms.group
                                for="name"
                                isRequired
                                :label="__('shopper::layout.forms.label.amount')"
                            >
                                <x-shopper::forms.input
                                    wire:model.defer="payment.amount"
                                    id="name"
                                    type="text"
                                    autocomplete="off"
                                    disabled
                                    placeholder="Apple, Nike, Samsung..."
                                />
                            </x-shopper::forms.group>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
