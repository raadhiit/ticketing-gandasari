<?php

namespace App\Livewire;

use Illuminate\Notifications\DatabaseNotification;
use Livewire\Component;

class NotificationBell extends Component
{
    public int $unreadCount = 0;

    public array $notifications = [];

    public bool $open = false;

    public bool $sidebar = false;

    public function mount(): void
    {
        $this->loadNotifications();
    }

    public function loadNotifications(): void
    {
        $user = auth()->user();
        if (! $user) {
            return;
        }

        $this->unreadCount = $user->unreadNotifications()->count();

        $this->notifications = $user->notifications()
            ->take(10)
            ->get()
            ->toArray();
    }

    public function markAsRead(string $notificationId): void
    {
        $notification = DatabaseNotification::find($notificationId);

        if ($notification && $notification->notifiable_id === auth()->id()) {
            $notification->markAsRead();
        }

        $this->loadNotifications();

        $data = $notification?->data;

        if ($data && isset($data['action_url'])) {
            $this->redirect($data['action_url'], navigate: true);
        }
    }

    public function markAllAsRead(): void
    {
        auth()->user()->unreadNotifications->markAsRead();

        $this->loadNotifications();
    }

    public function checkNewNotifications(): void
    {
        $this->loadNotifications();
    }

    public function toggle(): void
    {
        $this->open = ! $this->open;
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
