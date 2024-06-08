<?php
class PreguntasModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getPreguntaRandom($userId){
        // Traigo una pregunta que el usuario aún no ha respondido
        $stmt = $this->database->prepare("
        SELECT * FROM preguntas 
        WHERE _id NOT IN (
            SELECT PREGUNTA_ID FROM PREGUNTAS_JUGADAS WHERE USER_ID = ?
        )
    ");
        $preguntasSinResponder = $this->database->execute($stmt, ["i", $userId]);

        // Selecciono una pregunta random que no haya respondido el usuario
        $preguntaRandom = $preguntasSinResponder[array_rand($preguntasSinResponder)];

        return $preguntaRandom;
    }

    public function addPreguntaJugada($userId, $preguntaId, $esCorrecta)
    {
        $stmt = $this->database->prepare("INSERT INTO preguntas_jugadas (USER_ID, PREGUNTA_ID, ES_CORRECTA) VALUES (?, ?, ?)");
        $this->database->execute($stmt, ["iii", $userId, $preguntaId, $esCorrecta ? 1 : 0]);
    }

    public function getRespuestas($preguntaId){
        $stmt = $this->database->prepare("SELECT * FROM respuestas WHERE ID_PREGUNTA = ?");
        return $this->database->execute($stmt, ["i", $preguntaId]);
    }

    public function comprobarRespuesta($respuestaId){
        $stmt = $this->database->prepare("SELECT es_correcta FROM respuestas WHERE _ID = ?");
        $result = $this->database->execute($stmt, ["i", $respuestaId]);

        // Si la consulta devuelve al menos un resultado y es_correcta es 1, entonces la respuesta es correcta
        return !empty($result) && $result[0]['es_correcta'] == 1;
    }

}