<?php
class PreguntasModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getPreguntaRandom($userId)
    {
        $stmt = $this->database->prepare("
            SELECT * FROM PREGUNTAS 
            WHERE _id NOT IN (
                SELECT PREGUNTA_ID FROM PREGUNTAS_JUGADAS WHERE USER_ID = ?
            )
        ");
        $preguntasSinResponder = $this->database->execute($stmt, ["i", $userId]);

        if (empty($preguntasSinResponder)) {
            return null;
        }

        $preguntaRandom = $preguntasSinResponder[array_rand($preguntasSinResponder)];
        return $preguntaRandom;
    }

    public function addPreguntaJugada($userId, $preguntaId, $esCorrecta)
    {
        $stmt = $this->database->prepare("INSERT INTO PREGUNTAS_JUGADAS (USER_ID, PREGUNTA_ID, ES_CORRECTA) VALUES (?, ?, ?)");
        $this->database->execute($stmt, ["iii", $userId, $preguntaId, $esCorrecta ? 1 : 0]);
    }

    public function getRespuestas($preguntaId)
    {
        $stmt = $this->database->prepare("SELECT * FROM RESPUESTAS WHERE ID_PREGUNTA = ?");
        return $this->database->execute($stmt, ["i", $preguntaId]);
    }

    public function comprobarRespuesta($respuestaId)
    {
        $stmt = $this->database->prepare("SELECT es_correcta FROM RESPUESTAS WHERE _ID = ?");
        $result = $this->database->execute($stmt, ["i", $respuestaId]);

        return !empty($result) && $result[0]['es_correcta'] == 1;
    }
}
