<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Tables;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views;
use Shopper\Framework\Exceptions\GeneralException;
use Shopper\Framework\Repositories\Ecommerce\CollectionRepository;

class CollectionsTable extends DataTableComponent
{
    public $columnSearch = [
        'name' => null,
    ];

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setAdditionalSelects(['id', 'slug', 'sort'])
            ->setDefaultSort('name')
            ->setBulkActions([
                'deleteSelected' => __('shopper::layout.forms.actions.delete'),
                'duplicate' => __('shopper::layout.forms.actions.duplicate'),
            ]);
    }

    /**
     * @throws GeneralException
     */
    public function deleteSelected(): void
    {
        if (count($this->getSelected()) > 0) {
            (new CollectionRepository())
                ->makeModel()
                ->newQuery()
                ->whereIn('id', $this->getSelected())
                ->delete();

            Notification::make()
                ->title(__('shopper::components.tables.status.delete'))
                ->body(__('The attribute has successfully disabled!'))
                ->success()
                ->send();
        }

        $this->selected = [];

        $this->clearSelected();
    }

    /**
     * @throws GeneralException
     */
    public function duplicate(): void
    {
        foreach(array_unique($this->getSelected()) as $item)
        {
            // These are strings since they came from an HTML element
            $collection = (new CollectionRepository())->getById((int) $item);
            $newCollection = $collection->replicate();

            $copyNumber = $this->getCopyNumber($newCollection->name . '-copy-');
            $newCollection->name = $newCollection->name . '-copy-' . $copyNumber;
            $newCollection->slug = $newCollection->slug . '-copy-' . $copyNumber;
            $newCollection->created_at = now();
            $newCollection->updated_at = now();
            $newCollection->save();

            $mediaItems = $collection->getMedia(config('shopper.system.storage.disks.uploads'));
            $mediaItems->each(fn($mediaItem) => $mediaItem->copy($newCollection, config('shopper.system.storage.disks.uploads')));
        }

        $this->clearSelected();
    }

    private function getCopyNumber($name): int
    {
        return (new CollectionRepository())
                ->makeModel()
                ->where('name', 'like', '%' . $name . '%')
                ->count() + 1;
    }

    public function filters(): array
    {
        return [
            'type' => Views\Filters\SelectFilter::make(__('shopper::pages/collections.filter_type'))
                ->options([
                    '' => __('shopper::layout.forms.label.any'),
                    'auto' => __('shopper::pages/collections.automatic'),
                    'manual' => __('shopper::pages/collections.manual'),
                ])
                ->filter(fn (Builder $query, string $type) => $query->where('type', $type)),
        ];
    }

    public function columns(): array
    {
        return [
            Views\Column::make(__('shopper::layout.forms.label.name'), 'name')
                ->sortable()
                ->searchable()
                ->view('shopper::livewire.tables.cells.collections.name'),
            Views\Column::make(__('shopper::layout.forms.label.type'), 'type')
                ->view('shopper::livewire.tables.cells.collections.type'),
            Views\Column::make(__('shopper::pages/collections.product_conditions'), 'match_conditions')
                ->view('shopper::livewire.tables.cells.collections.conditions'),
            Views\Column::make(__('shopper::layout.forms.label.updated_at'), 'updated_at')
                ->view('shopper::livewire.tables.cells.date'),
        ];
    }

    /**
     * @throws GeneralException
     */
    public function builder(): Builder
    {
        return (new CollectionRepository())
            ->makeModel()
            ->newQuery()
            ->with('rules')
            ->when($this->columnSearch['name'] ?? null, fn ($query, $name) => $query->where('name', 'like', '%' . $name . '%'));
    }
}
