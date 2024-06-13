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

    public function postRegister() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $username = $_POST['username'];
        $password = $_POST['password'];
        $rep_password = $_POST['rep_password'];
        $surname = $_POST['surname'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $rutaProfilePic = $_FILES['profile_pic']['tmp_name'];
        $contenidoProfilePic = isset($rutaProfilePic) && $rutaProfilePic != '' ? file_get_contents($rutaProfilePic) : null; // Verifica si se proporciona una imagen
        $birth_year = $_POST['birth_year'];
        $gender = $_POST['gender'];
        $country = $_POST['country'];
        $city = $_POST['city'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];

        $hash = md5($username . $email . date("Y-m-d"));

        // Verifica si la imagen es nula o vacía
        if ($contenidoProfilePic === null || $rutaProfilePic == '') {
            $_SESSION['error'] = "Debe cargar una foto de perfil válida";
            $this->presenter->render("view/RegisterView.mustache", ['error' => $_SESSION['error']]); // Redirige de vuelta al formulario
            exit();
        }

        if ($this->model->register($username, $password, $rep_password, $email, $name, $surname, $hash, $contenidoProfilePic, $birth_year, $gender, $country, $city, $latitude, $longitude)) {
            $user = $this->model->getUserByUsername($username);
            $id = $user[0]['_id'];
            $hash = md5($username . $email . date("Y-m-d"));
            $link = "/Users/validateEmail?id=" . $id . "&hash=" . $hash;
            $this->presenter->render("view/RegisterSuccessView.mustache", ['link' => $link]);
        } else {
            $_SESSION['error'] = "Ocurrió un error al registrar al usuario";
            $this->presenter->render("view/RegisterView.mustache", ['error' => $_SESSION['error']]); // Redirige de vuelta al formulario en caso de error
            exit();
        }
    }

    public function validateEmail()  // Validar el correo electrónico
    {
        $userId = $_GET['id'];
        $validationCode = $_GET['hash'];

        $user = $this->model->getUserById($userId);

        if ($user && $user[0]['hash'] == $validationCode) {
            // El código de validación coincide, marcar el correo electrónico como validado
            $this->model->setEmailValidated($userId);
            $_SESSION['message'] = "Correo electrónico validado correctamente";
            $this->presenter->render("view/LoginView.mustache", ['message' => $_SESSION['message']]);

        } else {
            echo "El código de validación no coincide";
        }
    }

    public function postLogin()  // Procesar el login
    {
        $usernameOrEmail = $_POST['username'];
        $password = $_POST['password'];
        $user = $this->model->login($usernameOrEmail, $password);

        if ($user) {
            $_SESSION['user'] = $user;
            $username = isset($_SESSION['user']) && is_array($_SESSION['user']) ? $_SESSION['user'][0]['username'] : null;
            header("Location: /Home");
        } else {
            $_SESSION['error'] = "Usuario o contraseña incorrectos";
            $this->presenter->render("view/LoginView.mustache", ['error' => $_SESSION['error']]);
        }
    }

    public function getProfile()  // Obtener la vista de Perfil
    {
        $username = isset($_SESSION['user']) && is_array($_SESSION['user']) ? $_SESSION['user'][0]['username'] : null;
        $user = $this->model->getUserByUsername($username);
        $user[0]['profile_pic'] = base64_encode($user[0]['profile_pic']); // Convertir la imagen a base64 para mostrarla en la vista
        $this->presenter->render("view/ProfileView.mustache", ['user' => $user]);
    }

    public function logOut()  // Procesar el logout
    {
        session_destroy();
        header("Location: /Users/getLogin");
    }

}