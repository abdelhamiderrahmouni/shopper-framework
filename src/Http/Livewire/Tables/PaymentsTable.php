<?php

namespace Shopper\Framework\Http\Livewire\Tables;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Shopper\Framework\Exceptions\GeneralException;
use Shopper\Framework\Repositories\Ecommerce\PaymentRepository;

class PaymentsTable extends DataTableComponent
{
    public $columnSearch = [
        'order.number' => null,
        'client.first_name' => null,
        'amount' => null,
        'payment_method' => null,
        'transaction_id' => null,
        'payment_date' => null
    ];

    public function boot(): void
    {
        $this->queryString['columnSearch'] = ['except' => null];
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Order", "order.number")
                ->sortable(),
            Column::make("User", "user.first_name")
                ->format(fn($value, $row, Column $column) => $row->user?->first_name . ' ' . $row->user?->last_name)
                ->sortable(),
            Column::make("Transaction id", "transaction_id")
                ->sortable(),
            Column::make("Amount", "amount")
                ->format(fn($value, $row, Column $column) => $row->formatted_amount)
                ->sortable(),
            Column::make("Payment method", "payment_method")
                ->sortable(),
            Column::make("Status", "status")
                ->sortable(),
            Column::make("Payment date", "payment_date")
                ->sortable(),
        ];
    }

    /**
     * @throws GeneralException
     */
    public function builder(): Builder
    {
        return (new PaymentRepository())
            ->makeModel()
            ->newQuery()
            ->select('*')
            ->with(['user', 'order']);
    }
}
