<?php

namespace Cunningsoft\MessageBundle\Event;

use Cunningsoft\MessageBundle\Entity\Message;
use Symfony\Component\EventDispatcher\Event;

class MessageEvent extends Event
{
    /**
     * @var Message
     */
    private $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }
}
