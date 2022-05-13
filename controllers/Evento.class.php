<?php
class Evento extends Controller
{

    public function findEspectacles($dia) {

        require_once __DIR__ . '../../vendor/autoload.php';
        require_once "config/ini-config.php";

        require_once __DIR__ . "../../model/bootstrap.php";


    $consultaSQL = "SELECT e.TITOL , d.DATA, d.HORA from ENTRADA as t
        inner join EVENT as e on t.event_id = e.id 
                inner join DATA as d on t.data_id = d.id
        where d.DATA='$dia'";
    
    $querySQL = $entityManager->getConnection()->query($consultaSQL);
    $resultadoSQL = $querySQL->fetchAll();

    echo "<h1>Consulta SQL:</h1>";
    echo "<pre>";
        echo var_dump($resultadoSQL);
    echo "</pre>";
    }
    
}