<?php

namespace App\Http\Controllers;
use Mail;
use App\Mail\SendMail;
use Illuminate\Http\Request;

class DemoMailController extends Controller
{
    public function sendMail(){
        $Data =[
            'title'=> 'This a test mail',
            'body'=> 'This mail body',
        ];
        Mail::to('mr.subhansahib@gmail.com')->send(new SendMail($Data));
    }
}
