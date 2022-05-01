<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once "config/ini-config.php";
/*
$app = new App();
$app->run();
*/

use Thos\Data;
use Thos\Entrada;
use Thos\Event;
use Thos\Localitzacio;
use Thos\Pagament;
use Thos\Zona;

require_once "model/bootstrap.php";

$desc = "PLATEA";

$ref = "24831KMMT5YM14";

/*
$temas = $entityManager->getRepository("Thos\Zona")->FindBy(["descripcio" => $desc]);

foreach ($temas as $tema){

    //Devuelve String
    echo utf8_encode($tema->getId().' ' );
    echo utf8_encode($tema->getDescripcio().' ' );
}
*/

//Query builder ZONA
/*
$resultado1 = $entityManager->createQueryBuilder()
    ->select('t')->from('Thos\Zona','t')
    ->where('t.descripcio =:desc')
    ->setParameter('desc', $desc)
    ->getQuery()
    ->getResult();

echo "<pre>";
var_dump($resultado1);

echo "</pre>";

foreach ($resultado1 as $tema){
    echo utf8_encode($tema->getDescripcio().' ' );
}
*/


//Query builder

$resultado1 = $entityManager->createQueryBuilder()
    ->select('t')->from('Thos\Entrada','t')
    ->where('t.id =:ref')
    ->setParameter('ref', $ref)
    ->getQuery()
    ->getResult();

// echo "<pre>";
// var_dump($resultado1);

// echo "</pre>";

$fila;
$butaca;
$dni;




$mpdf = new \Mpdf\Mpdf();

   

    $html='
    <h1>Entrada </h1>
    <table style="width: 100%; border-collapse: collapse; border:1px solid" border="2">
    <tr>
        <td>Fila</td>
        <td>Butaca</td>
        <td>DNI</td>
    </tr>
    ';

    foreach ($resultado1 as $entrada){

        $html .='
        <tr>
            <td>'.$entrada->getFila().'</td>
            <td>'.$entrada->getButaca().'</td>
            <td>'.$entrada->getCompardor().'</td>
        </tr>
    </table>';
      
    }

   $mpdf->WriteHTML($html);
   $mpdf->Output('entrada.pdf');

  

//var_dump($products);
//echo $products;

if (isset ( $_GET ['ref'] )) {

    require_once __DIR__ . '/vendor/autoload.php';

    $refEntrada = $_GET['ref'];


    $entradas = $entityManager->getRepository(Zona::class);
    $entradas->findAll();


    var_dump( $entradas);

    foreach($entradas as $entrada){
        var_dump( $entrada->getDescripcio());
    }



/*
    //definimos la consulta
    $query = 'select * from ENTRADA WHERE ID ="'. $refEntrada.'";';
 
    //ejecutamos la consulta
    $st = $entityManager->getConnection()->query($query);
    
    //recuperamos las tuplas de resultados
   $result = $st->fetchAll();
*/
        //var_dump($entradas);

/*   
   $mpdf = new \Mpdf\Mpdf();

    $html='
   
    <h1>Entrada </h1>
    
    <table border=2>
    
    ';

   $mpdf->WriteHTML($html);
   $mpdf->Output('prueba.pdf');

  */  

}


/*
$queryAutores= $entityManager->getRepository(Entrada::class)->findAll();
echo "<h1>Query Autores:</h1>";
echo "<pre>";
var_dump($queryAutores);
echo "</pre>";
*/

?>
