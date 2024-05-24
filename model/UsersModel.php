<?php
class UsersModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getUserByUsername($username)
    {
        return $this->database->query("SELECT * FROM USUARIOS WHERE USUARIO = '$username'");
    }
}