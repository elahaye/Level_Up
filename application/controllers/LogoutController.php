<?php

class LogoutController extends Controller
{
    public function __construct()
    {
        session_destroy();
        // Redirect to the index page
        Router::redirectTo('home');
        exit();
    }
}
