<?php

declare(strict_types=1);

namespace Shopper\Framework\Traits;

use Filament\Notifications\Notification;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait WithUploadProcess
{
    public $files = [];

    public function removeMedia(int $id): void
    {
        Media::query()->find($id)?->delete();

        $this->emitSelf('mediaDeleted');

        Notification::make()
            ->title(__('Removed'))
            ->body(__('Media removed from the storage.'))
            ->success()
            ->send();
    }

    public function updateImagesOrder($values): void
    {
        foreach ($values as $value) {
            $media = Media::query()->find($value['value']);
            $media?->update(['order_column' => $value['order']]);
        }

        $this->emitSelf('mediaReordered');

        Notification::make()
            ->title(__('Media Updated'))
            ->body(__('Media order update.'))
            ->success()
            ->send();
    }
}
