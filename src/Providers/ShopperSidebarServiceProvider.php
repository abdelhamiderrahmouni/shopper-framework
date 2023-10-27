<?php

declare(strict_types=1);

namespace Shopper\Framework\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Maatwebsite\Sidebar\Infrastructure\SidebarFlusherFactory;
use Maatwebsite\Sidebar\Infrastructure\SidebarResolverFactory;
use Shopper\Framework\Sidebar\Domain\DefaultItem;
use Shopper\Framework\Sidebar\Presentation\ShopperSidebarRenderer;

class ShopperSidebarServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind SidebarResolver
        $this->app->bind(\Maatwebsite\Sidebar\Infrastructure\SidebarResolver::class, function (Application $app) {
            $resolver = SidebarResolverFactory::getClassName(
                $app['config']->get('shopper.system.cache.method')
            );

            return $app->make($resolver);
        });

        // Bind SidebarFlusher
        $this->app->bind(\Maatwebsite\Sidebar\Infrastructure\SidebarFlusher::class, function (Application $app) {
            $resolver = SidebarFlusherFactory::getClassName(
                $app['config']->get('shopper.system.cache.method')
            );

            return $app->make($resolver);
        });

        // Bind manager
        $this->app->singleton(\Maatwebsite\Sidebar\SidebarManager::class);

        // Bind Menu
        $this->app->bind(
            \Maatwebsite\Sidebar\Menu::class,
            \Maatwebsite\Sidebar\Domain\DefaultMenu::class
        );

        // Bind Group
        $this->app->bind(
            \Maatwebsite\Sidebar\Group::class,
            \Maatwebsite\Sidebar\Domain\DefaultGroup::class
        );

        // Bind Item
        $this->app->bind(
            \Maatwebsite\Sidebar\Item::class,
            DefaultItem::class
        );

        // Bind Badge
        $this->app->bind(
            \Maatwebsite\Sidebar\Badge::class,
            \Maatwebsite\Sidebar\Domain\DefaultBadge::class
        );

        // Bind Append
        $this->app->bind(
            \Maatwebsite\Sidebar\Append::class,
            \Maatwebsite\Sidebar\Domain\DefaultAppend::class
        );

        // Bind Renderer
        $this->app->bind(
            \Maatwebsite\Sidebar\Presentation\SidebarRenderer::class,
            ShopperSidebarRenderer::class
        );
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [
            \Maatwebsite\Sidebar\Menu::class,
            \Maatwebsite\Sidebar\Item::class,
            \Maatwebsite\Sidebar\Group::class,
            \Maatwebsite\Sidebar\Badge::class,
            \Maatwebsite\Sidebar\Append::class,
            \Maatwebsite\Sidebar\SidebarManager::class,
            \Maatwebsite\Sidebar\Presentation\SidebarRenderer::class,
            \Maatwebsite\Sidebar\Infrastructure\SidebarResolver::class,
        ];
    }
}
