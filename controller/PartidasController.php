<?php

class PartidasController
{

    private $presenter;
    private $usersModel;

    public function __construct($presenter, $partidasModel, $usersModel, )
    {
        $this->presenter = $presenter;
        $this->usersModel = $usersModel;
        $this->partidasModel = $partidasModel;
    }

    public function getPartidas()
    {
        $username = isset($_SESSION['user']) ? $_SESSION['user'][0]['username'] : null;
        $userId = isset($_SESSION['user']) ? $_SESSION['user'][0]['_id'] : null;
        $partidas = $this->partidasModel->getPartidasByUser($userId);
        $this->presenter->render("view/PartidasView.mustache", [
            'username' => $username,
            'partidas' => $partidas
        ]);
    }
}