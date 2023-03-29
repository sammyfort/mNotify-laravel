<?php


namespace Velstack\Mnotify\Notifications;


class MnotifyCustomMessage
{
    private string $message;
    protected string $sender;

    public function message(string $message): static
    {
        $this->message = $message;
        return $this;
    }

    public function content(): string
    {
        return $this->message;
    }

    public function sender(string $sender): static
    {
         $this->sender = $sender;
         return $this;
    }
}
