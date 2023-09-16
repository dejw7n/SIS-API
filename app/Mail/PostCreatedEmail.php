<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class PostCreatedEmail extends Mailable
{
    public function build()
    {
        return $this->from('no-reply-sis@spsul.cz')
            ->subject('Nový příspěvek na ŠIS')
            ->withSwiftMessage(function ($message) {
                $message->setBody('Byl přidán nový příspěvek na šis.', 'text/plain');
            });
    }
}
