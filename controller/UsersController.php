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
            $username = isset($_SESSION['user']) && is_array($_SESSION['user']) ? $_SESSION['user'][0]['username'] : null;
            $this->presenter->render("view/HomeView.mustache", ['username' => $username]);
        } else {
            $_SESSION['error'] = "Usuario o contraseña incorrectos";
            $this->presenter->render("view/LoginView.mustache", ['error' => $_SESSION['error']]);
        }
    }

    public function logOut()  // Procesar el logout
    {
        session_destroy();
        header("Location: /tp-final/index.php");
    }

}