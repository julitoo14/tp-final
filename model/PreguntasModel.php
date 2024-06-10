<?php
class PreguntasModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getPreguntaRandom($userId, $promedioAciertosUsuario){
        // Define el rango de dificultad basado en el promedio de aciertos del usuario
        if ($promedioAciertosUsuario <= 30) { // si el usuario promedia menos de 30% de aciertos le doy preguntas faciles
            $dificultadMin = 70;
            $dificultadMax = 100;
        } elseif ($promedioAciertosUsuario <= 69) { // si el usuario promedia entre 30% y 69% de aciertos le doy preguntas de dificultad media
            $dificultadMin = 30;
            $dificultadMax = 70;
        } else { // si el usuario promedia más de 70% de aciertos le doy preguntas dificiles
            $dificultadMin = 0;
            $dificultadMax = 30;
        }

        // Traigo una pregunta que el usuario aún no ha respondido y que se encuentra en el rango de dificultad adecuado
        $stmt = $this->database->prepare("
        SELECT * FROM preguntas 
        WHERE _id NOT IN (
            SELECT PREGUNTA_ID FROM PREGUNTAS_JUGADAS WHERE USER_ID = ?
        ) AND DIFICULTAD >= ? AND DIFICULTAD <= ?
    ");
        $preguntasSinResponder = $this->database->execute($stmt, ["iii", $userId, $dificultadMin, $dificultadMax]);

        // Si no hay preguntas sin responder en el rango de dificultad adecuado, traigo una pregunta sin responder sin tener en cuenta la dificultad
        if (empty($preguntasSinResponder)) {
            $stmt = $this->database->prepare("
            SELECT * FROM PREGUNTAS
            WHERE _ID NOT IN (
                SELECT PREGUNTA_ID FROM PREGUNTAS_JUGADAS WHERE USER_ID = ?
            )
        ");
            $preguntasSinResponder = $this->database->execute($stmt, ["i", $userId]);

            // Si no hay preguntas sin responder, reinicio las partidas del usuario y vuelvo a intentar traer una pregunta
            if (empty($preguntasSinResponder)) {
                $this->reiniciarPartidasUsuario($userId);
                return $this->getPreguntaRandom($userId, $promedioAciertosUsuario);
            }
        }

        // Selecciono una pregunta random que no haya respondido el usuario
        $preguntaRandom = $preguntasSinResponder[array_rand($preguntasSinResponder)];

        return $preguntaRandom;
    }

    public function getColorCategoria($preguntaId) {

        $stmt = $this->database->prepare("
        SELECT c.colorCategoria 
        FROM preguntas p
        JOIN categorias c ON p.id_categoria = c.id
        WHERE p._id = ?
    ");
        $stmt->bind_param("i", $preguntaId);
        $stmt->execute();
        $result = $stmt->get_result();
        $colorCategoria = $result->fetch_assoc();

        return $colorCategoria ? $colorCategoria['colorCategoria'] : null;
    }

    public function agregarVezJugada($preguntaId){
        $stmt = $this->database->prepare("UPDATE PREGUNTAS SET VECES_JUGADA = VECES_JUGADA + 1 WHERE _ID = ?");
        $this->database->execute($stmt, ["i", $preguntaId]);

        // Actualiza la dificultad de la pregunta
        $this->actualizarDificultad($preguntaId);
    }

    public function agregarVezCorrecta($preguntaId){
        $stmt = $this->database->prepare("UPDATE PREGUNTAS SET VECES_ACERTADA = VECES_ACERTADA + 1 WHERE _ID = ?");
        $this->database->execute($stmt, ["i", $preguntaId]);

        // Actualiza la dificultad de la pregunta
        $this->actualizarDificultad($preguntaId);
    }

    private function actualizarDificultad($preguntaId){
        // Obtén el número de veces que la pregunta fue jugada
        $stmt = $this->database->prepare("SELECT VECES_JUGADA FROM PREGUNTAS WHERE _ID = ?");
        $vecesJugada = $this->database->execute($stmt, ["i", $preguntaId])[0]['VECES_JUGADA'];

        // Obtén el número de veces que la pregunta fue respondida correctamente
        $stmt = $this->database->prepare("SELECT VECES_ACERTADA FROM PREGUNTAS WHERE _ID = ?");
        $vecesAcertada = $this->database->execute($stmt, ["i", $preguntaId])[0]['VECES_ACERTADA'];

        // Calcula el porcentaje de aciertos
        $porcentajeAciertos = ($vecesAcertada / $vecesJugada) * 100;

        // Actualiza la dificultad de la pregunta en la base de datos
        $stmt = $this->database->prepare("UPDATE PREGUNTAS SET DIFICULTAD = ? WHERE _ID = ?");
        $this->database->execute($stmt, ["di", $porcentajeAciertos, $preguntaId]);
    }

    public function reiniciarPartidasUsuario($userId) {
        $stmt = $this->database->prepare("DELETE FROM PREGUNTAS_JUGADAS WHERE USER_ID = ?");
        $this->database->execute($stmt, ["i", $userId]);
    }

    public function addPreguntaJugada($userId, $preguntaId, $esCorrecta)
    {
        $stmt = $this->database->prepare("INSERT INTO PREGUNTAS_JUGADAS (USER_ID, PREGUNTA_ID, ES_CORRECTA) VALUES (?, ?, ?)");
        $this->database->execute($stmt, ["iii", $userId, $preguntaId, $esCorrecta ? 1 : 0]);
    }

    public function getDificultad($preguntaId){
        // Obtén el número de veces que la pregunta fue jugada
        $stmt = $this->database->prepare("SELECT VECES_JUGADA FROM PREGUNTAS WHERE _ID = ?");
        $vecesJugada = $this->database->execute($stmt, ["i", $preguntaId])[0]['VECES_JUGADA'];

        // Obtén el número de veces que la pregunta fue respondida correctamente
        $stmt = $this->database->prepare("SELECT VECES_ACERTADA FROM PREGUNTAS WHERE _ID = ?");
        $vecesAcertada = $this->database->execute($stmt, ["i", $preguntaId])[0]['VECES_ACERTADA'];

        // Calcula el porcentaje de aciertos
        $porcentajeAciertos = ($vecesAcertada / $vecesJugada) * 100;

        return $porcentajeAciertos;
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