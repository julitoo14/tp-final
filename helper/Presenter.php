<?php

class Presenter
{

    public function __construct()
    {
    }

    public function render($view, $data = [])
    {
        if (!file_exists("view/template/header.mustache")) {
            throw new Exception("Header file does not exist");
        }
        if (!file_exists($view)) {
            throw new Exception("View file does not exist: $view");
        }
        if (!file_exists("view/template/footer.mustache")) {
            throw new Exception("Footer file does not exist");
        }

        include_once("view/template/header.mustache");
        include_once($view);
        include_once("view/template/footer.mustache");
    }
}