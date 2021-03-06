<?php

namespace Yugen\DbMailLog\Listeners;

use Yugen\DbMailLog\DbMailLogProvider;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class StoreMailInDatabase
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MessageSent  $event
     * @return void
     */
    public function handle(MessageSent $event)
    {
        $email = DbMailLogProvider::getEmailInstance([
            'from' => $event->message->getFrom(),
            'sender' => $event->message->getSender(),
            'reply_to' => $event->message->getReplyTo(),
            'to' => $event->message->getTo(),
            'cc' => $event->message->getCc(),
            'bcc' => $event->message->getBcc(),
            'subject' => $event->message->getSubject(),
            'body' => $event->message->getBody(),
        ]);
        $email->save();
    }
}
