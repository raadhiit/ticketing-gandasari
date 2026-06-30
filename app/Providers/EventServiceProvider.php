<?php

namespace App\Providers;

use App\Listeners\NotificationSubscriber;
use App\Listeners\TicketHistorySubscriber;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        parent::boot();
    }

    protected function configureSubscribers(): void
    {
        $this->app->events->subscribe(TicketHistorySubscriber::class);
        $this->app->events->subscribe(NotificationSubscriber::class);
    }
}
