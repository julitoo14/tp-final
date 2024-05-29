<?php
class UsersModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function register($username, $password, $email, $name, $surname, $hash)
    {
        if ($this->userExists($username, $email)) {
            $_SESSION['error'] = "El usuario o email ya existe";
            return false;
        }

        $stmt = $this->database->prepare("INSERT INTO `USUARIOS`(`USERNAME`, `PASSWORD`, `EMAIL`, `NAME`, `SURNAME`,`HASH`) VALUES (?, ?, ?, ?, ?, ?)");
        $this->database->execute($stmt, ["ssssss", $username, $password, $email, $name, $surname, $hash]);
        return true;
    }

    public function login($usernameOrEmail, $password)
    {
        $stmt = $this->database->prepare("SELECT * FROM USUARIOS WHERE (USERNAME = ? OR EMAIL = ?) AND PASSWORD = ?");
        return $this->database->execute($stmt, ["sss", $usernameOrEmail, $usernameOrEmail, $password]);
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
}