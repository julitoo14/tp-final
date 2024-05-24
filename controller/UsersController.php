<?php

class UsersController
{

    private $model;
    private $presenter;

    public function __construct($model, $presenter)
    {
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function getLogin()
    {
        $this->presenter->render("view/LoginView.mustache");
    }

    public function getRegister()
    {
        $this->presenter->render("view/RegisterView.mustache");
    }

    public function postRegister()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $this->model->register($username, $password);

        $this->presenter->render("view/RegisterSuccessView.mustache");
    }

    public function postLogin()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $user = $this->model->login($username, $password);

        if ($user) {
            $_SESSION['user'] = $user;
            $this->presenter->render("view/HomeView.mustache");
        } else {
            $this->presenter->render("view/LoginErrorView.mustache");
        }
    }


}