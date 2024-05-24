<?php

class HomeController
{

    private $presenter;

    public function __construct($presenter)
    {
        $this->presenter = $presenter;
    }

    public function get()
    {
        $username = isset($_SESSION['user']) ? $_SESSION['user'][0]['username'] : null;
        $this->presenter->render("view/HomeView.mustache", ['username' => $username]);
    }
}