<?php

class HomeController
{
    private $presenter;
    private $partidasModel;

    public function __construct($presenter, $partidasModel)
    {
        $this->presenter = $presenter;
        $this->partidasModel = $partidasModel;
    }

    public function get() {
        $username = isset($_SESSION['user']) ? $_SESSION['user'][0]['username'] : 'algo';
        $userId = isset($_SESSION['user']) ? $_SESSION['user'][0]['_id'] : null;
        $puntajeFinal = $this->partidasModel->getPuntajeFinal($userId);

        // Depuración
        error_log(print_r($puntajeFinal, true));

        $this->presenter->render("view/HomeView.mustache", [
            'username' => $username,
            'puntaje' => isset($puntajeFinal[0]['puntaje']) ? $puntajeFinal[0]['puntaje'] : null
        ]);
    }

}

