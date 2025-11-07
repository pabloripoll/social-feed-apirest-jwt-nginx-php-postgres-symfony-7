<?php

namespace App\Domain\Member\Message;

use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage]
final class UserRegisterMessage
{
    public array $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }
}
