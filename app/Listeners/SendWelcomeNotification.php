<?php declare(strict_types=1);

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Services\Interfaces\MailServiceInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendWelcomeNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private readonly MailServiceInterface $mailService
    ) {}

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        $this->mailService->sendWelcomeEmail($event->userId);
    }
}
