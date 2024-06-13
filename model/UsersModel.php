<?php
class UsersModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function register($username, $password, $rep_password, $email, $name, $surname, $hash, $profile_pic, $birth_year, $gender, $country, $city)
    {
        if ($this->userExists($username, $email)) {
            $_SESSION['error'] = "El usuario o email ya existe";
            return false;
        }

        if ($password != $rep_password) {
            $_SESSION['error'] = "Las contraseñas no coinciden";
            return false;
        }

        if($profile_pic == null) {
            $_SESSION['error'] = "Debe cargar una foto de perfil válida";
        }

        $stmt = $this->database->prepare("INSERT INTO `USUARIOS`(`USERNAME`, `PASSWORD`, `EMAIL`, `NAME`, `SURNAME`,`HASH`,`PROFILE_PIC`,`BIRTH_YEAR`, `GENDER`, `COUNTRY`, `CITY`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $this->database->execute($stmt, ["sssssssisss", $username, $password, $email, $name, $surname, $hash, $profile_pic, $birth_year, $gender, $country, $city]);
        return true;
    }

    public function login($usernameOrEmail, $password)
    {
        $stmt = $this->database->prepare("SELECT * FROM USUARIOS WHERE (USERNAME = ? OR EMAIL = ?) AND PASSWORD = ?");
        return $this->database->execute($stmt, ["sss", $usernameOrEmail, $usernameOrEmail, $password]);
    }

    public function agregarPreguntaJugada($userId)
    {
        $stmt = $this->database->prepare("UPDATE USUARIOS SET PREGUNTAS_JUGADAS = PREGUNTAS_JUGADAS + 1 WHERE _ID = ?");
        $this->database->execute($stmt, ["i", $userId]);

        $this->actualizarDificultad($userId);
    }

    public function agregarPreguntaCorrecta($userId)
    {
        $stmt = $this->database->prepare("UPDATE USUARIOS SET PREGUNTAS_ACERTADAS = PREGUNTAS_ACERTADAS + 1 WHERE _ID = ?");
        $this->database->execute($stmt, ["i", $userId]);

        $this->actualizarDificultad($userId);
    }

    public function actualizarDificultad($userId){
        // Obtén el número de veces que el usuario ha respondido preguntas
        $stmt = $this->database->prepare("SELECT PREGUNTAS_JUGADAS FROM USUARIOS WHERE _ID = ?");
        $preguntasJugadas = $this->database->execute($stmt, ["i", $userId])[0]['PREGUNTAS_JUGADAS'];

        // Obtén el número de veces que el usuario ha respondido correctamente
        $stmt = $this->database->prepare("SELECT PREGUNTAS_ACERTADAS FROM USUARIOS WHERE _ID = ?");
        $preguntasAcertadas = $this->database->execute($stmt, ["i", $userId])[0]['PREGUNTAS_ACERTADAS'];

        // Calcula el porcentaje de aciertos
        $porcentajeAciertos = ($preguntasAcertadas / $preguntasJugadas) * 100;

        // Actualiza la dificultad del usuario en la base de datos
        $stmt = $this->database->prepare("UPDATE USUARIOS SET DIFICULTAD = ? WHERE _ID = ?");
        $this->database->execute($stmt, ["di", $porcentajeAciertos, $userId]);
    }

    public function getPromedioAciertos($userId)
    {
        $stmt = $this->database->prepare("SELECT DIFICULTAD FROM USUARIOS WHERE _ID = ?");
        return $this->database->execute($stmt, ["i", $userId])[0]['DIFICULTAD'];
    }

    public function userExists($username, $email)
    {
        $stmt = $this->database->prepare("SELECT * FROM USUARIOS WHERE USERNAME = ? OR EMAIL = ?");
        $result = $this->database->execute($stmt, ["ss", $username, $email]);
        return !empty($result);
    }

    public function setEmailValidated($userId)
    {
        $stmt = $this->database->prepare("UPDATE USUARIOS SET EMAIL_VALIDATED = 1 WHERE _ID = ?");
        $this->database->execute($stmt, ["i", $userId]);
    }

    public function getUserByUsername($username)
    {
        $stmt = $this->database->prepare("SELECT * FROM USUARIOS WHERE USERNAME = ?");
        return $this->database->execute($stmt, ["s", $username]);
    }

    public function getUserById($userId)
    {
        $stmt = $this->database->prepare("SELECT * FROM USUARIOS WHERE _ID = ?");
        return $this->database->execute($stmt, ["i", $userId]);
    }

    public function getMaxScore($userId)
    {

        $stmt = $this->database->prepare("SELECT SUM(puntaje) as TOTAL_SCORE FROM PARTIDAS WHERE USER_ID = ?");
        return $this->database->execute($stmt, ["i", $userId])[0]['TOTAL_SCORE'];
    }

    public function getTopUsers()
    {
        $stmt = $this->database->prepare("SELECT USERNAME, SUM(puntaje) as TOTAL_SCORE FROM USUARIOS JOIN PARTIDAS ON USUARIOS._ID = PARTIDAS.USER_ID GROUP BY USERNAME ORDER BY TOTAL_SCORE DESC");
        return $this->database->execute($stmt);
    }
}