<?php

declare(strict_types=1);

namespace Shopper\Framework\Repositories\Ecommerce;

use Shopper\Framework\Repositories\BaseRepository;

class PaymentRepository extends BaseRepository
{
    public function model(): string
    {
        return config('shopper.system.models.payment');
    }
}
