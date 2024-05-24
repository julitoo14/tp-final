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
        $this->presenter->render("view/loginView.mustache");
    }

    public function getRegister()
    {
        $this->presenter->render("view/registerView.mustache");
    }

    public function postRegister()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $this->model->register($username, $password);

        $this->presenter->render("view/registerSuccessView.mustache");
    }

    public function postLogin()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($this->model->login($username, $password)) {
            $this->presenter->render("view/homeView.mustache");
        } else {
            $this->presenter->render("view/loginErrorView.mustache");
        }
    }

    public function login($username, $password)
    {
        $user = $this->model->getUserByUsername($username);

        if ($user && $password === $user['password']) {
            // Iniciar sesión del usuario
            $_SESSION['user'] = $user;
            return true;
        }

        // Si los detalles no son correctos, devolver false
        return false;
    }

}