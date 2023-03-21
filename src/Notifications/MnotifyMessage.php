<?php

namespace Velstack\Mnotify;

class MnotifyMessage
{
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
