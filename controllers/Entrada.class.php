<?php

use chillerlan\QRCode\QRCode;

class Entrada extends Controller
{

    public function pdfGenerator($ref)
    {

        require_once __DIR__ . '../../vendor/autoload.php';
        require_once "config/ini-config.php";

        require_once __DIR__ . "../../model/bootstrap.php";

       if( $entrada = $entityManager->getRepository("Thos\Entrada")->find($ref)){
        
       

        /* Valores para la Entrada */
        $tituloEvent = $entrada->getEvent()->getTitol();
        $subtitolEvent = $entrada->getEvent()->getSubtitol();
        
        $pattern = "/img/";
        $imatgeEvent = $entrada->getEvent()->getImatge();
    

            if(preg_match($pattern, $imatgeEvent)){
                $imgEvento = $imatgeEvent;
            }else{
                $imgEvento = 'img/'. $imatgeEvent;
            }
       
        
        
        $fila = $entrada->getFila();
        $butaca = $entrada->getButaca();
        $dni = $entrada->getCompardor();
        $fechaEvento = $entrada->getData()->getData();
        $horaEvento = $entrada->getData()->getHora();
        $lugarEvento = $entrada->getLocalitzacio()->getLloc();
        $direcionEvento = $entrada->getLocalitzacio()->getAdreca();
        $localitatEvento = $entrada->getLocalitzacio()->getLocalitat();
        $zonaEvento = $entrada->getZona()->getDescripcio();
        $confirmacion = $entrada->getPagament()->getReferenciaExterna();
        $this->generateCodeBar($confirmacion);

        $data = 'http://localhost/M7_Entrada/?ref='.$ref.'';

        /* Estructura Entrada */
        $htmlEntrada = '
        <style media="print">
            p { 
                color: red; 
            }
            .event-img {
                width:50%;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                border:2px solid";
                text-align: center;
             }
             th, td {
                width: 25%;
                font-family: Arial;
                text-align: center;
                vertical-align: top;
                font-size: 20px;
                border: 1px solid #000;
                border-collapse: collapse;
             }
             th{
                text-align: center;
                background-color: #961847;
                color: white;
             }
             .center-img{
                 text-align: center;
                 margin:0 auto;
                 width: 100%
                 height: 300px;
             }
             .alert{
                border: 2px;
                border-style: dotted;
                width: 100%;
                
             }
             .logo{
                 width: 150px;
             }
             .titulo{
                 background-color: #961847;
                 color: white;
                 font-family: Arial;
             }
             .qr-code{
                 width: 150px;
             }
             
             
            
        </style>

        <img class="logo" src="img/atrapalo-logo.png">
        <table>

        <caption>
        
        
                <h1 class="titulo">' . $tituloEvent . '</h1>
                <p>' . $subtitolEvent . '</p>
        </caption>
        <thead>
            <tr>
                <th colspan="2">
                    <h2>Fila</h2>
                </th>
                <th colspan="2">
                    <h2>Butaca</h2>
                </th>
                <th colspan="2">
                    <h2>Zona</h2>
                </th>
                

            </tr>
        </thead>

        <tbody>
            <tr>
                <td colspan="2">' . $fila . '</td>
                <td colspan="2">' . $butaca . '</td>
                <td colspan="2">' . $zonaEvento . '</td>
                
           
            </tr>
            <tr>
                    <th colspan="2">
                    <h2>Fecha</h2>
                </th>
                <th colspan="2">
                    <h2>Lugar</h2>
                </th>
                <th colspan="2">
                    <h2>QR</h2>
                </th>
           
            </tr>
            <tr>

                <td colspan="2">' . $fechaEvento . '<br> a las '. $horaEvento .'</td>
                <td colspan="2">' . $lugarEvento .' - '.$direcionEvento.'('.$localitatEvento.')</td>
                <td colspan="2">' .$this->generateQR($data). '</td>
           
            </tr>
            
        </tbody>
        
        <tfoot>
            <tr>
                <td scope="row" colspan="6" class="center-img">
             <div>
               
                <img class="event-img" src="'.$imgEvento.'"/>
                <p class="alert">Enseña el código QR en la entrada para acceder al evento</p>
                
             </div>
             <b>'. $confirmacion.'</b>
                </td>
                
            </tr>
            <br>
            <img src="img/CodeBar.png"/>
            
            
        </tfoot>
      

     </table>'
       
     ;

             
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->SetWatermarkImage('img/pagado.png', 0.2, array(140,140));
        $mpdf->showWatermarkImage = true;
        $mpdf->WriteHTML($htmlEntrada);
        $mpdf->Output();
        
            }
            echo "<p class='error'>No existe la entrada con la ref: <b>".$ref."</b></p>";
    }

    public function generateQR($url)
    {

        $qr = new QRCode;
        return '<img class="qr-code" src="' . $qr->render($url) . '" alt="QR Code" />';
    }

    public function generateCodeBar($num)
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        
        file_put_contents('img/CodeBar.png', $generator->getBarcode($num, $generator::TYPE_CODE_128));
        
    }
}
