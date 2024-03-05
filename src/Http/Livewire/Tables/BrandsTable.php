<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Tables;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views;
use Shopper\Framework\Exceptions\GeneralException;
use Shopper\Framework\Repositories\Ecommerce\BrandRepository;

class BrandsTable extends DataTableComponent
{
    public $columnSearch = [
        'name' => null,
    ];

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setAdditionalSelects(['id', 'is_enabled', 'description'])
            ->setDefaultSort('name')
            ->setBulkActions([
                'deleteSelected' => __('shopper::layout.forms.actions.delete'),
                'enabled' => __('shopper::layout.forms.actions.enable'),
                'disabled' => __('shopper::layout.forms.actions.disable'),
                'duplicate' => __('shopper::layout.forms.actions.duplicate'),
            ])
            ->setTdAttributes(function (Views\Column $column) {
                if ($column->isField('slug')) {
                    return [
                        'class' => 'text-secondary-500 font-normal',
                    ];
                }

                return [];
            });
    }

    public function boot(): void
    {
        $this->queryString['columnSearch'] = ['except' => null];
    }

    /**
     * @throws GeneralException
     */
    public function deleteSelected(): void
    {
        if (count($this->getSelected()) > 0) {
            (new BrandRepository())->makeModel()
                ->newQuery()
                ->whereIn('id', $this->getSelected())
                ->delete();

            Notification::make()
                ->title(__('shopper::components.tables.status.delete'))
                ->body(__('shopper::components.tables.messages.delete', ['name' => strtolower(__('shopper::layout.forms.label.brand'))]))
                ->success()
                ->send();
        }

        $this->selected = [];

        $this->clearSelected();
    }

    /**
     * @throws GeneralException
     */
    public function enabled(): void
    {
        if (count($this->getSelected()) > 0) {
            (new BrandRepository())->makeModel()
                ->newQuery()
                ->whereIn('id', $this->getSelected())
                ->update(['is_enabled' => true]);

            Notification::make()
                ->title(__('shopper::components.tables.status.updated'))
                ->body(__('shopper::components.tables.messages.enabled', ['name' => strtolower(__('shopper::layout.forms.label.brand'))]))
                ->success()
                ->send();
        }

        $this->selected = [];

        $this->clearSelected();
    }

    /**
     * @throws GeneralException
     */
    public function disabled(): void
    {
        if (count($this->getSelected()) > 0) {
            (new BrandRepository())->makeModel()
                ->newQuery()
                ->whereIn('id', $this->getSelected())
                ->update(['is_enabled' => false]);

            Notification::make()
                ->title(__('shopper::components.tables.status.updated'))
                ->body(__('shopper::components.tables.messages.disabled', ['name' => strtolower(__('shopper::layout.forms.label.brand'))]))
                ->success()
                ->send();
        }

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
            $brand = (new BrandRepository())->getById((int) $item);
            $newBrand = $brand->replicate();

            $copyNumber = $this->getCopyNumber($newBrand->name . '-copy-');
            $newBrand->name = $newBrand->name . '-copy-' . $copyNumber;
            $newBrand->slug = $newBrand->slug . '-copy-' . $copyNumber;
            $newBrand->created_at = now();
            $newBrand->updated_at = now();
            $newBrand->save();

            $mediaItems = $brand->getMedia(config('shopper.system.storage.disks.uploads'));
            $mediaItems->each(fn($mediaItem) => $mediaItem->copy($newBrand, config('shopper.system.storage.disks.uploads')));
        }

        $this->clearSelected();
    }

    private function getCopyNumber($name): int
    {
        return (new BrandRepository())
                ->makeModel()
                ->where('name', 'like', '%' . $name . '%')
                ->count() + 1;
    }

    public function columns(): array
    {
        return [
            Views\Column::make(__('shopper::layout.forms.label.name'), 'name')
                ->sortable()
                ->searchable()
                ->view('shopper::livewire.tables.cells.brands.name'),
            Views\Column::make(__('shopper::layout.forms.label.website'), 'website')
                ->view('shopper::livewire.tables.cells.brands.site'),
            Views\Column::make(__('shopper::layout.forms.label.slug'), 'slug'),
            Views\Column::make(__('shopper::layout.forms.label.updated_at'), 'updated_at')
                ->view('shopper::livewire.tables.cells.date'),
        ];
    }

    /**
     * @throws GeneralException
     */
    public function builder(): Builder
    {
        return (new BrandRepository())
            ->makeModel()
            ->newQuery()
            ->with('media')
            ->when($this->columnSearch['name'] ?? null, fn ($query, $name) => $query->where('name', 'like', '%' . $name . '%'));
    }
}
