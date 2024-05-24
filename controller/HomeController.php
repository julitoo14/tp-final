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
        return $this->presenter->render("view/HomeView.mustache");
    }
}