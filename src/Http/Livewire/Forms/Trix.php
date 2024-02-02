<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Forms;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Trix extends Component
{
    public string $trixId;

    public ?string $value = null;

    public string $eventName = 'trix:valueUpdated';

    public function mount(string $value = null, string|null $eventName = null): void
    {
        $this->value = $value;
        $this->trixId = 'trix-' . uniqid();

        if ($eventName) {
            $this->eventName = $eventName;
        }
    }

    public function updatedValue(string $value): void
    {
        $this->emitUp($this->eventName, $value);
    }

    public function render(): View
    {
        return view('shopper::livewire.forms.trix');
    }
}
