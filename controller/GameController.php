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
        $partida = $this->partidasModel->getUltimaPartida($userId);

        // Verificar si existe una partida en curso y si ya ha sido finalizada
        if ($partida && $partida['finalizada'] == 1) {
            // Si la partida está finalizada, no se permite comenzar una nueva
            $_SESSION['mensajeError'] = "Ya has completado todas las preguntas.";
            header("Location: index.php?controller=Home&action=get");
            exit;
        } elseif ($partida) {
            // Si el usuario tiene una partida en curso, se redirige a la página de juego
            $_SESSION['partidaId'] = $partida['_id'];
            header("Location: index.php?controller=Game&action=getQuestion");
            exit;
        }

        // Si el usuario no tiene una partida en curso ni una partida finalizada, se inicia una nueva partida
        $partidaId = $this->partidasModel->addPartida($userId);
        $_SESSION['partidaId'] = $partidaId;
        header("Location: index.php?controller=Game&action=getQuestion");
        exit;
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
