<?php
class Evento extends Controller
{


    public function findEspectacles($dia)
    {

        require_once __DIR__ . '../../vendor/autoload.php';
        require_once "config/ini-config.php";

        require_once __DIR__ . "../../model/bootstrap.php";

        $consultaSQL = "SELECT e.TITOL ,e.SUTBITOL, d.DATA, d.HORA,l.LLOC from ENTRADA as t
                inner join EVENT as e on t.event_id = e.id
                inner join DATA as d on t.data_id = d.id
                inner join LOCALITZACIO as l on t.loc_id = l.id
        where d.DATA='$dia'";

        $querySQL = $entityManager->getConnection()->query($consultaSQL);
        $resultadoSQL = $querySQL->fetchAll();

        
        $this->createXML($resultadoSQL);
        
    
        echo "Fichero guardado en la raiz del proyecto Espectacles.xml";
        
        //include "./Espectacles.xml";
          
           
     

    }

    public function createXML($data)
    {

        //Obetenemos el length del array de los datos que nos pasan
        $longitud = count($data);

        //creamos un nuevo DOMDocument
        $xmlDoc = new DOMDocument('1.0', 'utf-8');

        if($longitud != 0){


        //creamos el TAG padre
        $root = $xmlDoc->appendChild($xmlDoc->createElement("eventos"));

        //Iteramos dato a dato
        for ($i = 0; $i < $longitud; $i++) {
            
            $tabEventos = $root->appendChild($xmlDoc->createElement("concierto"));

            //obtenemos el valor de cada elemento y creamos un elemento
            $tabEventos->appendChild($xmlDoc->createElement("titol", $data[$i]['TITOL']));
            $tabEventos->appendChild($xmlDoc->createElement("subtitol", $data[$i]['SUTBITOL']));
            $tabEventos->appendChild($xmlDoc->createElement("data", $data[$i]['DATA']));
            $tabEventos->appendChild($xmlDoc->createElement("hora", $data[$i]['HORA']));
            $tabEventos->appendChild($xmlDoc->createElement("lugar", $data[$i]['LLOC']));
            
        }
    }
    else{
        $root = $xmlDoc->appendChild($xmlDoc->createElement("eventos", "Sin eventos"));
    }
        header("Content-Type: text/plain");

        //con esta ocpión agregamos tabulaciones a nuestro fichero XML
        $xmlDoc->formatOutput = true;

        //Nombre y ubicación donde guardamos el fichero
        $file_name = 'Espectacles.xml';
        $xmlDoc->save("./" . $file_name);

        return $file_name;
    }
    
}
