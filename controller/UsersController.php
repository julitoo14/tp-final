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

    public function getLogin() // Obtener la vista de Login
    {
        $this->presenter->render("view/LoginView.mustache");
    }

    public function getRegister()   // Obtener la vista de Registro
    {
        $this->presenter->render("view/RegisterView.mustache");
    }

    public function postRegister()  // Procesar el registro
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $this->model->register($username, $password);

        $this->presenter->render("view/RegisterSuccessView.mustache");
    }

    public function postLogin()  // Procesar el login
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $user = $this->model->login($username, $password);

        if ($user) {
            $_SESSION['user'] = $user;
            $username = $_SESSION['user'][0]['username'];
            $this->presenter->render("view/HomeView.mustache", ['username' => $username]);
        } else {
            $this->presenter->render("view/LoginErrorView.mustache");
        }
    }


}