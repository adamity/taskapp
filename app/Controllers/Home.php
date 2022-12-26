<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return view('Home/index');
    }

    public function testEmail()
    {
        // $email = service('email');
        // $email->setTo('muhammadzulkifli1710@gmail.com');
        // $email->setSubject('Email Test');
        // $email->setMessage('<h1>Hello World</h1>');

        // if ($email->send()) {
        //     echo "Message sent";
        // } else {
        //     echo $email->printDebugger();
        // }

        $email = service('email');

        $email->setTo('muhammadzulkifli1710@gmail.com');
        $email->setSubject('Account Activation');

        $message = view('Signup/activation_email', [
            'token' => 12345,
        ]);

        $email->setMessage($message);

        if ($email->send(false)) {
            echo "Message sent";
        } else {
            echo $email->printDebugger();
        }
    }
}
