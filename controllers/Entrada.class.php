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
        $imatgeEvent = $entrada->getEvent()->getImatge();
        $fila = $entrada->getFila();
        $butaca = $entrada->getButaca();
        $dni = $entrada->getCompardor();
        $fechaEvento = $entrada->getData()->getData();
        $horaEvento = $entrada->getData()->getHora();
        $lugarEvento = $entrada->getLocalitzacio()->getLloc();

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
                text-align: center;
                text-align: left;
                vertical-align: top;
                border: 1px solid #000;
                border-collapse: collapse;
             }
             .center{
                 text-align: center;
                 margin:0 auto;
             }
             .alert{
                border: 2px;
                border-style: dotted;
                width: 100%;
                
             }
             
            
        </style>

        
        <table>

        <caption>
                <h1>' . $tituloEvent . '</h1>
                <p>' . $subtitolEvent . '</p>
        </caption>
        <thead>
            <tr>
                <th>
                    <h2>Fila</h2>
                </th>
                <th>
                    <h2>Butaca</h2>
                </th>
                <th colspan="2">
                    <h2>Fecha</h2>
                </th>
                <th colspan="2">
                    <h2>Lugar</h2>
                </th>

            </tr>
        </thead>

        <tbody>
            <tr>
                <td>' . $fila . '</td>
                <td>' . $butaca . '</td>
                <td colspan="2">' . $fechaEvento . ' a las '. $horaEvento .'</td>
                <td colspan="2">' . $lugarEvento . '</td>
           
            </tr>
            
        </tbody>
        
        <tfoot>
            <tr>
                <th scope="row" colspan="6">
             <div class="center">
                <img class="event-img" src="img/' . $imatgeEvent . '">
                '.$this->generateQR($data).'
                <p class="alert">Enseña este código QR en la entrada para acceder al evento</p>
                
             </div>
                </th>
            </tr>
        </tfoot>
        '. $this->generateCodeBar('0X123172389X') . '

     </table>'
       
     ;

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($htmlEntrada);
        $mpdf->Output('Entrada'.$ref.'.pdf');
        
        echo "<h1>Entrada generada correctamente</h1>";

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
