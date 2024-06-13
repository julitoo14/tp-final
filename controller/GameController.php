<?php

class GameController
{
    private $presenter;
    private $preguntasModel;
    private $partidasModel;
    private $usersModel;

    public function __construct($presenter, $preguntasModel, $partidasModel, $usersModel)
    {
        $this->presenter = $presenter;
        $this->preguntasModel = $preguntasModel;
        $this->partidasModel = $partidasModel;
        $this->usersModel = $usersModel;
    }

    public function getQuestion()
    {
        $userId = isset($_SESSION['user']) ? $_SESSION['user'][0]['_id'] : null;
        $partidaId = $_SESSION['partidaId'];
        $puntos = $this->partidasModel->getPuntaje($partidaId);
        $promedioJugador = $this->usersModel->getPromedioAciertos($userId);

        if (isset($_SESSION['pregunta'])) {
            $preguntaRandom = $_SESSION['pregunta'];
        } else {
            $preguntaRandom = $this->preguntasModel->getPreguntaRandom($userId, $promedioJugador);
            $_SESSION['pregunta'] = $preguntaRandom;
        }

        $preguntaId = $preguntaRandom['_id'];
        $colorCategoria = $this->preguntasModel->getColorCategoria($preguntaId);
        $respuestas = $this->preguntasModel->getRespuestas($preguntaId);

        $this->presenter->render("view/GameView.mustache", [
            'colorCategoria' => $colorCategoria,
            'pregunta' => $preguntaRandom,
            'respuestas' => $respuestas,
            'puntos' => $puntos[0]['puntaje']
        ]);
    }

    public function startGame()
    {
        $userId = isset($_SESSION['user']) ? $_SESSION['user'][0]['_id'] : null;
        $partidaId = $this->partidasModel->addPartida($userId);
        $_SESSION['partidaId'] = $partidaId;
        $this->getQuestion();
    }

    public function postAnswer()
    {
        $userId = isset($_SESSION['user']) ? $_SESSION['user'][0]['_id'] : null;
        $preguntaId = $_POST['preguntaId'];
        $respuestaUsuarioId = $_POST['respuestaId'];

        $esCorrecta = $this->preguntasModel->comprobarRespuesta($respuestaUsuarioId);

        $this->preguntasModel->agregarVezJugada($preguntaId);
        $this->usersModel->agregarPreguntaJugada($userId);

        if ($esCorrecta) {
            $this->preguntasModel->agregarVezCorrecta($preguntaId);
            $this->usersModel->agregarPreguntaCorrecta($userId);
        }

        $partidaId = $_SESSION['partidaId'];
        $puntos = $this->partidasModel->getPuntaje($partidaId);
        $puntos = $esCorrecta ? $puntos[0]['puntaje'] + 1 : $puntos[0]['puntaje'];
        $this->partidasModel->actualizarPuntosPartida($partidaId, $puntos);

        $this->preguntasModel->addPreguntaJugada($userId, $preguntaId, $esCorrecta);

        unset($_SESSION['pregunta']);

        if ($esCorrecta) {
            header("Location: /Game/getQuestion");
        } else {
            $_SESSION['mensaje'] = 'Perdiste';
            $_SESSION['puntaje'] = $puntos;
            header("Location: /Home");
        }
    }

    public function timeUp()
    {
        $userId = isset($_SESSION['user']) ? $_SESSION['user'][0]['_id'] : null;
        $partidaId = $_SESSION['partidaId'];
        $puntos = $this->partidasModel->getPuntaje($partidaId);
        $puntos = $puntos[0]['puntaje'];

        $_SESSION['mensaje'] = 'Perdiste';
        $_SESSION['puntaje'] = $puntos;

        header("Location: /Home");
        exit();
    }

    public function getPartidaId()
    {
        echo $_SESSION['partidaId'];
    }
}
