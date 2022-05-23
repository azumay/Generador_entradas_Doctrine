<?php

class App extends Controller
{

    public function run()
    {

        $this->dispatch();
    }

    public function dispatch()
    {

        if (isset($_GET['ref'])) {
            $ref = $this->sanitize($_GET['ref']);
            $ref = trim ( $_GET ['ref'], '/' ); //trec la barra final
            $ref = filter_var ( $ref, FILTER_SANITIZE_URL ); //elimino caràcters especial

            $controller_name = "Entrada";
            $action = "pdfGenerator";
        } else if (isset($_GET['data'])) {
            $ref = $this->sanitize($_GET['data']);
            $controller_name = "Evento";
            $action = "findEspectacles";

        } else {
            $controller_name = "Sortida";
            $action = "Crea";
        }

        if (file_exists(__DIR__ . "/../controllers/$controller_name.class.php")) {
            $controller = new $controller_name(); //instancio el controlador

            if (method_exists($controller, $action)) {
                $controller->$action($ref); //executo l'acció
            } else {
                $error = "La acción no existe."; //no hi ha acció
                include __DIR__ . "/../views/error.tpl.php";
            }
        } else {
            $error = "El controlador no existe."; //no hi ha controlador
            include __DIR__ . "/../views/error.tpl.php";
        }
    }
}
