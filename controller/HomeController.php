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
        $username = $_SESSION['user'][0]['username'];
        return $this->presenter->render("view/HomeView.mustache", ['username' => $username]);
    }
}