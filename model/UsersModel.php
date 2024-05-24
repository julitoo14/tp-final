<?php
class UsersModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function register($username, $password)
    {
        $this->database->execute("INSERT INTO `usuarios`(`username`, `password`) VALUES ('" . $username ."','" . $password . "')");
    }

    public function login($username, $password)
    {
        return $this->database->query("SELECT * FROM USUARIOS WHERE USERNAME = '$username' AND PASSWORD = '$password'");
    }

    public function getUserByUsername($username)
    {
        return $this->database->query("SELECT * FROM USUARIOS WHERE USERNAME = '$username'");
    }
}