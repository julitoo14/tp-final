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
        //Obtengo el id del usuario logueado
        $userId = isset($_SESSION['user']) ? $_SESSION['user'][0]['_id'] : null;
        //Obtengo el id de la partida actual y luego el puntaje de la misma
        $partidaId = $_SESSION['partidaId'];
        $puntos = $this->partidasModel->getPuntaje($partidaId);
        $promedioJugador = $this->usersModel->getPromedioAciertos($userId);
        $preguntaRandom = $this->preguntasModel->getPreguntaRandom($userId, $promedioJugador);
        $respuestas = $this->preguntasModel->getRespuestas($preguntaRandom['_id']);
        $this->presenter->render("view/GameView.mustache", ['pregunta' => $preguntaRandom, 'respuestas' => $respuestas, 'puntos' => $puntos[0]['puntaje']]);
    }

    public function startGame(){
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

        // Comprueba si la respuesta del usuario es correcta
        $esCorrecta = $this->preguntasModel->comprobarRespuesta($respuestaUsuarioId);

        // Actualiza la cantidad de veces que fue respondida la pregunta
        $this->preguntasModel->agregarVezJugada($preguntaId);
        $this->usersModel->agregarPreguntaJugada($userId);
        // Actualiza la cantidad de veces que fue respondida correctamente la pregunta
        if ($esCorrecta) {
            $this->preguntasModel->agregarVezCorrecta($preguntaId);
            $this->usersModel->agregarPreguntaCorrecta($userId);
        }

        // Actualiza el puntaje de la partida
        $partidaId = $_SESSION['partidaId'];
        $puntos = $this->partidasModel->getPuntaje($partidaId);
        $puntos = $esCorrecta ? $puntos[0]['puntaje'] + 1 : $puntos[0]['puntaje'];
        $this->partidasModel->actualizarPuntosPartida($partidaId, $puntos);

        // Registra la pregunta como jugada
        $this->preguntasModel->addPreguntaJugada($userId, $preguntaId, $esCorrecta);


        // Redirige al usuario a la siguiente pregunta o muestra los resultados
        // ...
        $this->getQuestion();
    }

}