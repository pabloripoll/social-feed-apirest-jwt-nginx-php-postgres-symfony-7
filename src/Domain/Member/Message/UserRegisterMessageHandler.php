<?php

namespace App\Domain\Member\Message;

use App\Domain\Member\Message\UserRegisterMessage;
use App\Domain\Member\Mail\UserRegisterMail;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class UserRegisterMessageHandler
{
    public function __construct(private UserRegisterMail $registrationMail)
    {
    }

    public function __invoke(UserRegisterMessage $message): void
    {
        // Send the email (or perform other job tasks)
        $this->registrationMail->send($message->payload);
    }
}
