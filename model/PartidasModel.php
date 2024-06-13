<?php

class PartidasModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getPartidas($userId){
        $stmt = $this->database->prepare("SELECT * FROM PARTIDAS WHERE USER_ID = ?");
        return $this->database->execute($stmt, ["i", $userId]);
    }

    public function getPartida($partidaId){
        $stmt = $this->database->prepare("SELECT * FROM PARTIDAS WHERE _ID = ?");
        return $this->database->execute($stmt, ["i", $partidaId]);
    }

    public function getPuntaje($partidaId){
        $stmt = $this->database->prepare("SELECT puntaje FROM PARTIDAS WHERE _ID = ?");
        return $this->database->execute($stmt, ["i", $partidaId]);
    }

    public function addPartida($userId){
        $stmt = $this->database->prepare("INSERT INTO PARTIDAS (USER_ID) VALUES (?)");
        $this->database->execute($stmt, ["i", $userId]);
        return $this->database->getInsertId();
    }

    public function actualizarPuntosPartida($partidaId, $puntos){
        $stmt = $this->database->prepare("UPDATE PARTIDAS SET puntaje = ? WHERE _ID = ?");
        $this->database->execute($stmt, ["ii", $puntos, $partidaId]);
    }

    public function getPartidasByUser($userId)
    {
        $stmt = $this->database->prepare("SELECT fecha_creacion, puntaje FROM PARTIDAS WHERE USER_ID = ? ORDER BY fecha_creacion DESC");
        return $this->database->execute($stmt, ["i", $userId]);
    }

}