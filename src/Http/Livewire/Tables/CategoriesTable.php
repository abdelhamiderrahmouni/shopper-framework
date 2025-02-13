<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Tables;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views;
use Shopper\Framework\Exceptions\GeneralException;
use Shopper\Framework\Repositories\Ecommerce\CategoryRepository;

class CategoriesTable extends DataTableComponent
{
    public ?string $defaultSortColumn = 'name';

    public $columnSearch = [
        'name' => null,
    ];

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setAdditionalSelects(['id', 'is_enabled', 'description', 'parent_id'])
            ->setBulkActions([
                'deleteSelected' => __('shopper::layout.forms.actions.delete'),
                'enabled' => __('shopper::layout.forms.actions.enable'),
                'disabled' => __('shopper::layout.forms.actions.disable'),
                'duplicate' => __('shopper::layout.forms.actions.duplicate'),
            ])
            ->setTdAttributes(function (Views\Column $column) {
                if ($column->isField('slug')) {
                    return [
                        'class' => 'text-secondary-500 dark:text-secondary-400 truncate font-normal',
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
            (new CategoryRepository)->makeModel()
                ->newQuery()
                ->whereIn('id', $this->getSelected())
                ->delete();

            Notification::make()
                ->title(__('shopper::components.tables.status.delete'))
                ->body(__('shopper::components.tables.messages.delete', ['name' => __('shopper::words.category')]))
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
            (new CategoryRepository)->makeModel()
                ->newQuery()
                ->whereIn('id', $this->getSelected())
                ->update(['is_enabled' => true]);

            Notification::make()
                ->title(__('shopper::components.tables.status.updated'))
                ->body(__('shopper::components.tables.messages.enabled', ['name' => __('shopper::words.category')]))
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
            (new CategoryRepository)
                ->makeModel()
                ->newQuery()
                ->whereIn('id', $this->getSelected())
                ->update(['is_enabled' => false]);

            Notification::make()
                ->title(__('shopper::components.tables.status.updated'))
                ->body(__('shopper::components.tables.messages.disabled', ['name' => __('shopper::words.category')]))
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
        foreach (array_unique($this->getSelected()) as $item) {
            // These are strings since they came from an HTML element
            $category = (new CategoryRepository)->getById((int) $item);
            $newCategory = $category->replicate();

            $copyNumber = $this->getCopyNumber($newCategory->name . '-copy-');
            $newCategory->name = $newCategory->name . '-copy-' . $copyNumber;
            $newCategory->slug = $newCategory->slug . '-copy-' . $copyNumber;
            $newCategory->created_at = now();
            $newCategory->updated_at = now();
            $newCategory->save();

            $mediaItems = $category->getMedia(config('shopper.system.storage.disks.uploads'));
            $mediaItems->each(fn ($mediaItem) => $mediaItem->copy($newCategory, config('shopper.system.storage.disks.uploads')));
        }

        $this->clearSelected();
    }

    private function getCopyNumber($name): int
    {
        return (new CategoryRepository)
            ->makeModel()
            ->where('name', 'like', '%' . $name . '%')
            ->count() + 1;
    }

    public function filters(): array
    {
        return [
            'is_enabled' => Views\Filters\SelectFilter::make(__('shopper::words.is_enabled'))
                ->options([
                    '' => __('shopper::layout.forms.label.any'),
                    'yes' => __('shopper::layout.forms.label.yes'),
                    'no' => __('shopper::layout.forms.label.no'),
                ])
                ->filter(
                    fn (Builder $builder, string $value) => match ($value) {
                        'yes' => $builder->where('is_enabled', true),
                        'no' => $builder->where('is_enabled', false),
                    }
                ),
        ];
    }

    public function columns(): array
    {
        return [
            Views\Column::make(__('shopper::layout.forms.label.name'), 'name')
                ->sortable()
                ->searchable()
                ->format(
                    fn ($value, $row, Views\Column $column) => view('shopper::livewire.tables.cells.categories.name')
                        ->with('category', $row->load('media'))
                ),
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
        return (new CategoryRepository)
            ->makeModel()
            ->newQuery()
            ->when($this->columnSearch['name'] ?? null, fn ($query, $name) => $query->where('name', 'like', '%' . $name . '%'));
    }
}
