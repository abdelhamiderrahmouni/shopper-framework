<?php

declare(strict_types=1);

namespace Shopper\Framework\Repositories;

class ChannelRepository extends BaseRepository
{
    public function model(): string
    {
        return config('shopper.system.models.channel');
    }
}
