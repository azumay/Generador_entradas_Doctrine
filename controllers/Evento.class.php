<?php
class Evento extends Controller
{

    public function findEspectacles($dia)
    {

        require_once __DIR__ . '../../vendor/autoload.php';
        require_once "config/ini-config.php";

        require_once __DIR__ . "../../model/bootstrap.php";

        $consultaSQL = "SELECT e.TITOL , d.DATA, d.HORA from ENTRADA as t
        inner join EVENT as e on t.event_id = e.id
                inner join DATA as d on t.data_id = d.id
        where d.DATA='$dia'";

        $querySQL = $entityManager->getConnection()->query($consultaSQL);
        $resultadoSQL = $querySQL->fetchAll();

     /*   echo "<h1>Consulta SQL:</h1>";
        echo "<pre>";
        echo var_dump($resultadoSQL);
        echo "</pre>";
*/
        echo $this->createXML($resultadoSQL);
    }

    public function createXML($data)
    {

        //Obetenemos el length del array de los datos que nos pasan
        $longitud = count($data);

        //creamos un nuevo DOMDocument
        $xmlDoc = new DOMDocument('1.0', 'utf-8');

        //$root = $xmlDoc->createElement('conciertos');
        //creamos el TAG padre
        $padre = $xmlDoc->createElement("conciertos");

        //Iteramos dato a dato
        for ($i = 0; $i < $longitud; $i++) {

            $root = $xmlDoc->appendChild($xmlDoc->createElement("evento"));

            //obtenemos el valor de cada elemento y creamos un elemento
            $root->appendChild($xmlDoc->createElement("titol", $data[$i]['TITOL']));
            $root->appendChild($xmlDoc->createElement("data", $data[$i]['DATA']));
            $root->appendChild($xmlDoc->createElement("hora", $data[$i]['HORA']));
           
        }

       $root->appendChild($padre);
       

        header("Content-Type: text/plain");

        //con esta ocpión agregamos tabulaciones a nuestro fichero XML
        $xmlDoc->formatOutput = true;

        //Nombre y ubicación donde guardamos el fichero
        $file_name = 'Espectacles.xml';
        $xmlDoc->save("./" . $file_name);

        //Devolvemos el fichero
        return $file_name;
    }
}
