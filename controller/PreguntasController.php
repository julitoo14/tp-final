<?php

class PreguntasController
{
    private $presenter;
    private $preguntasModel;

    public function __construct($presenter, $preguntasModel)
    {
        $this->presenter = $presenter;
        $this->preguntasModel = $preguntasModel;
    }

    public function getCrearPregunta()
    {

        $this->presenter->render("view/CrearPreguntaView.mustache");

    }

    public function agregarPregunta()
    {
        $roleId = isset($_SESSION['user']) ? $_SESSION['user'][0]['rol'] : null;
        $roleId1 = $roleId == 1;
        $roleId3 = $roleId == 3;
        $idEstado = $roleId == 1 ? 2 : 1; // Si el rol es 1 (editor), el id_estado es 2. De lo contrario, es 1.
        $required = ['idCategoria', 'descripcion', 'opcionA', 'opcionB', 'opcionC', 'opcionD', 'respuestaCorrecta'];
        $errorMessage = "Error: Todos los campos son obligatorios";

        foreach ($required as $field) {
            if (!isset($_POST[$field])) {
                echo $errorMessage;
                return;
            }
        }
        $idCategoria = $_POST['idCategoria'];
        $descripcion = $_POST['descripcion'];
        $opcionA = $_POST['opcionA'];
        $opcionB = $_POST['opcionB'];
        $opcionC = $_POST['opcionC'];
        $opcionD = $_POST['opcionD'];
        $respuestaCorrecta = $_POST['respuestaCorrecta'];

        if (!$this->preguntasModel->buscarPreguntaPorDescripcion($descripcion)) {
            $this->preguntasModel->agregarPregunta($idCategoria, $descripcion, $opcionA, $opcionB, $opcionC, $opcionD, $respuestaCorrecta, $idEstado);
            $this->presenter->render("view/nuevaPreguntaExitosaView.mustache", ['roleId1' => $roleId1, 'roleId3' => $roleId3]);
        } else {
            $_SESSION['error'] = "Esta pregunta ya existe! intenta con otra";
            $this->presenter->render("view/CrearPreguntaView.mustache", ['error' => $_SESSION['error']]);
        }
    }

    public function getPreguntasAceptadas()
    {
        $username = isset($_SESSION['user']) ? $_SESSION['user'][0]['username'] : null;
        $data = [
            'username' => $username,
            'preguntasAceptadas' => $this->preguntasModel->getPreguntasAceptadas(),
            'edit' => true
        ];

        $this->presenter->render("view/PreguntasView.mustache", $data);
    }

    public function getPreguntasReportadas()
    {
        $username = isset($_SESSION['user']) ? $_SESSION['user'][0]['username'] : null;
        $data = [
            'username' => $username,
            'preguntasReportadas' => $this->preguntasModel->getPreguntasReportadas(),
            'repport' => true
        ];

        $this->presenter->render("view/PreguntasView.mustache", $data);
    }

    public function getPreguntasSugeridas()
    {
        $username = isset($_SESSION['user']) ? $_SESSION['user'][0]['username'] : null;
        $data = [
            'username' => $username,
            'preguntasSugeridas' => $this->preguntasModel->getPreguntasSugeridas(),
            'suggested' => true
        ];

        $this->presenter->render("view/PreguntasView.mustache", $data);
    }

    private function redirectToQuestionPage($idEstado)
    {
        switch ($idEstado) {
            case 1:
                $location = "/Preguntas/getPreguntasSugeridas";
                break;
            case 2:
                $location = "/Preguntas/getPreguntasAceptadas";
                break;
            case 3:
                $location = "/Preguntas/getPreguntasReportadas";
                break;
            default:
                $location = "/Preguntas/getPreguntasAceptadas";
                break;
        }
        header("Location: " . $location);
        exit();
    }

    public function borrar()
    {
        $id = $_POST["id"];
        $idEstado = $this->preguntasModel->getPregunta($id);
        $this->preguntasModel->borrarPregunta($id);
        $this->redirectToQuestionPage($idEstado[0]["id_estado"]);
    }
}