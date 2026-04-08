<?php declare(strict_types=1);

namespace App\Services\Interfaces;

interface MailServiceInterface
{
    public function sendWelcomeEmail(int $userId): void;
}
