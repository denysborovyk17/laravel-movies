<?php declare(strict_types=1);

namespace App\Services;

use App\Mail\WelcomeEmail;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\MailServiceInterface;
use Illuminate\Contracts\Mail\Mailer;

class MailService implements MailServiceInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly Mailer $mailer
    ) {}

    public function sendWelcomeEmail(int $userId): void
    {
        $user = $this->userRepository->find($userId);
    
        $this->mailer->to($user)->send(new WelcomeEmail($user));
    }
}
