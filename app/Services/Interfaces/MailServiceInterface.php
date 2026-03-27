<?php

namespace App\Services\Interfaces;

use App\Models\User;

interface MailServiceInterface
{
    public function sendWelcomeEmail(int $userId): void;
}
