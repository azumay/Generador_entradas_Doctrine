<?php

use chillerlan\QRCode\QRCode;

class Entrada extends Controller
{

    public function pdfGenerator($ref)
    {

        require_once __DIR__ . '../../vendor/autoload.php';
        require_once "config/ini-config.php";

        require_once __DIR__ . "../../model/bootstrap.php";

        $entrada = $entityManager->getRepository("Thos\Entrada")->find($ref);
        

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

        $data = 'http://localhost/M7_Entrada/?ref='.$ref.'';

        /* Estructura Entrada */
        $htmlEntrada = '
        <style media="print">
            p { 
                color: red; 
            }
            .event-img {
                width: 50%;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                border:2px solid";
                text-align: center;
             }
             th, td {
                width: 25%;
               
                text-align: left;
                vertical-align: top;
                border: 1px solid #000;
                border-collapse: collapse;
             }
             th{
                text-align: center;
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
             }

             
            
        </style>

        
        <table>

        <caption>
        <img class="logo" src="img/logo.png">
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

                <td colspan="2">' . $fechaEvento . ' a las '. $horaEvento .'</td>
                <td colspan="2">' . $lugarEvento .' - '.$direcionEvento.'('.$localitatEvento.')</td>
                <td colspan="2">' .$this->generateQR($data). '</td>
           
            </tr>
            
        </tbody>
        
        <tfoot>
            <tr>
                <th scope="row" colspan="6" class="center-img">
             <div>
               
                <img class="event-img" src="'.$imgEvento.'"/>
                <p class="alert">Enseña el código QR en la entrada para acceder al evento</p>
                
             </div>
                </th>
            </tr>
        </tfoot>
        '. $this->generateCodeBar($confirmacion) . '

     </table>'
       
     ;

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($htmlEntrada);
        $mpdf->Output('Entrada.pdf');
        
        echo "<h1>Entrada generada correctamente</h1>";

        $pattern = "/img/";
             echo $imatgeEvent. '<br>';
        
        //echo preg_match($pattern, $imatgeEvent);

        echo $imgEvento;
             

        //echo $htmlEntrada;
    }

    public function generateQR($url)
    {

        $qr = new QRCode;
        return '<img src="' . $qr->render($url) . '" alt="QR Code" />';
    }

    public function generateCodeBar($num)
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
        return $generator->getBarcode($num, $generator::TYPE_CODE_128);
    }
}
