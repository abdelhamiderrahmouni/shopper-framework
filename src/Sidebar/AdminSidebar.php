<?php

declare(strict_types=1);

namespace Shopper\Framework\Sidebar;

use Maatwebsite\Sidebar\Menu;
use Maatwebsite\Sidebar\ShouldCache;
use Maatwebsite\Sidebar\Sidebar;
use Maatwebsite\Sidebar\Traits\CacheableTrait;

class AdminSidebar implements ShouldCache, Sidebar
{
    use CacheableTrait;

    public function __construct(protected Menu $menu) {}

    public function build(): void
    {
        $sidebarBuilder = config('shopper.settings.sidebar.builder');

        event(new $sidebarBuilder($this->menu));
    }

    public function getMenu(): Menu
    {
        return $this->menu;
    }
}
