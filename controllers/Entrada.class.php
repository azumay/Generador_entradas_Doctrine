<?php

use Doctrine\ORM\EntityManagerInterface;

use chillerlan\QRCode\QRCode;


class Entrada extends Controller{

    

    public function pdfGenerator($ref){


        require_once __DIR__ . '../../vendor/autoload.php';
        require_once "config/ini-config.php";
        

        require_once __DIR__."../../model/bootstrap.php";

        $entrada = $entityManager->getRepository("Thos\Entrada")->find($ref);

        /* Valores para la Entrada */
        $tituloEvent = $entrada->getEvent()->getTitol();
        $subtitolEvent = $entrada->getEvent()->getSubtitol();
        $imatgeEvent = $entrada->getEvent()->getImatge();

        $data = 'www.google.es';
        
        /* Estructura Entrada */
        $htmlEntrada='
        <h1>Entrada </h1>
        <table style="
                        width: 100%; 
                        border-collapse: collapse; 
                        border:1px solid" 
                        border="2">
        <caption>
        <h1>'.$tituloEvent.'</h1>
        <p>'.$subtitolEvent.'</p>
        </caption>
        <tr>
            <td><img src="img/'.$imatgeEvent.'"></td>
            <td>Fila</td>
            <td>Butaca</td>
            <td>DNI</td>
        </tr>
        <tr>
            <td>'.$entrada->getFila().'</td>
            <td>'.$entrada->getButaca().'</td>
            <td>'.$entrada->getCompardor().'</td>
            <td>'.$this->generateQR($data).'</td>
            <td>'.$this->generateCodeBar('0X123172389X').'</td>
            </tr>
     </table>';

       
        
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($htmlEntrada);
        $mpdf->Output('prueba.pdf');
        
        
        

        
    }

    public function generateQR($url){

        $qr = new QRCode;


        return '<img src="'.$qr->render($url).'" alt="QR Code" />';
    }

    public function generateCodeBar($num){
        $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
      return  $generator->getBarcode($num, $generator::TYPE_CODE_128);
    }
}