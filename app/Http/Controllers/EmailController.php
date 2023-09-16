<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\PostCreatedEmail;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendTestEmail()
    {
        $email = Mail::to('dejw7n@gmail.com')->send(new PostCreatedEmail());
        return response()->json($email);
    }
}
