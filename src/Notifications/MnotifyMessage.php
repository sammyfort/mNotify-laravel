<?php

namespace Velstack\Mnotify\Notifications;

use Velstack\Mnotify\Traits\Setters;

class MnotifyMessage
{
    use Setters;
    private string $message;

    public function message(string $message): static
    {
        $this->message = $message;
        return $this;
    }

    public function content(): string{
        return $this->message;
    }

}
