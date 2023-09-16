<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class PostCreatedEmail extends Mailable
{
    public function build()
    {
        return $this->from('no-reply-sis@spsul.cz')
            ->subject('Nový příspěvek na ŠIS')
            ->view('emails.my_email')
            ->with([
                'message' => 'Byl přidán nový příspěvek na šis.',
            ]);
    }
}
