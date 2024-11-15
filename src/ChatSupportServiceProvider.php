<?php

namespace Dotgix\Chatsupport;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class ChatSupportServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register package configuration or bindings here if needed
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrations();
        $this->loadRoutes();
        $this->loadViews();
        $this->loadAssets();
        $this->registerLivewireComponents();
        $this->registerBladeComponents();
    }

    /**
     * Load package migrations.
     */
    protected function loadMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    /**
     * Load package routes.
     */
    protected function loadRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/channels.php');
    }

    /**
     * Load package views.
     */
    protected function loadViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'chatsupport');
    }

    /**
     * Publish assets and views for customization.
     */
    protected function loadAssets(): void
    {
        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/vendor/chatsupport'),
        ], 'chatsupport-views');

        $this->publishes([
            __DIR__ . '/public' => public_path('vendor/chatsupport'),
        ], 'chatsupport-assets');
    }

    /**
     * Register Livewire components.
     */
    protected function registerLivewireComponents(): void
    {
        Livewire::component('admin.chat-box', \Dotgix\Chatsupport\app\Livewire\Admin\ChatBox::class);
        Livewire::component('admin.chat-list', \Dotgix\Chatsupport\app\Livewire\Admin\ChatList::class);
        Livewire::component('admin.chat', \Dotgix\Chatsupport\app\Livewire\Admin\Chat::class);
        Livewire::component('admin.index', \Dotgix\Chatsupport\app\Livewire\Admin\Index::class);

        Livewire::component('consultant.chat-box', \Dotgix\Chatsupport\app\Livewire\Consultant\ChatBox::class);
        Livewire::component('consultant.chat-list', \Dotgix\Chatsupport\app\Livewire\Consultant\ChatList::class);
        Livewire::component('consultant.chat', \Dotgix\Chatsupport\app\Livewire\Consultant\Chat::class);
        Livewire::component('consultant.index', \Dotgix\Chatsupport\app\Livewire\Consultant\Index::class);

        Livewire::component('visitor.chat', \Dotgix\Chatsupport\app\Livewire\Visitor\Chat::class);
        Livewire::component('visitor.index', \Dotgix\Chatsupport\app\Livewire\Visitor\Index::class);
    }

    /**
     * Register Blade components.
     */
    protected function registerBladeComponents(): void
    {
        Blade::component('chatsupport::layouts.app', 'chatsupport-app-layout');
        Blade::component('chatsupport::layouts.guest', 'chatsupport-guest-layout');
    }
}
