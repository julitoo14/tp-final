<?php

class GameController
{
    private $presenter;
    private $preguntasModel;
    private $partidasModel;

    public function __construct($presenter, $preguntasModel, $partidasModel)
    {
        $this->presenter = $presenter;
        $this->preguntasModel = $preguntasModel;
        $this->partidasModel = $partidasModel;
    }

    public function getQuestion()
    {
        $userId = isset($_SESSION['user']) ? $_SESSION['user'][0]['_id'] : null;
        $partidaId = $_SESSION['partidaId'];
        $puntos = $this->partidasModel->getPuntaje($partidaId);
        $preguntaRandom = $this->preguntasModel->getPreguntaRandom($userId);

        if ($preguntaRandom === null) {
            // No hay más preguntas, redirigir al HomeView con el puntaje final
            $this->partidasModel->finalizarPartida($partidaId, $puntos[0]['puntaje']);
            $_SESSION['puntaje'] = $puntos[0]['puntaje'];
            header("Location: index.php?controller=Home&action=get");
            exit;
        }

        $respuestas = $this->preguntasModel->getRespuestas($preguntaRandom['_id']);
        $this->presenter->render("view/GameView.mustache", ['pregunta' => $preguntaRandom, 'respuestas' => $respuestas, 'puntos' => $puntos[0]['puntaje']]);
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

        $partidaId = $_SESSION['partidaId'];
        $puntos = $this->partidasModel->getPuntaje($partidaId);
        $puntos = $esCorrecta ? $puntos[0]['puntaje'] + 1 : $puntos[0]['puntaje'];
        $this->partidasModel->actualizarPuntosPartida($partidaId, $puntos);

        $this->preguntasModel->addPreguntaJugada($userId, $preguntaId, $esCorrecta);

        $this->getQuestion();
    }
}