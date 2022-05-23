<?php

use chillerlan\QRCode\QRCode;

class Entrada extends Controller
{

    private $fila;
    private $butaca;
    private $dni;
    private $fechaEvento;
    private $horaEvento;
    private $lugarEvento;
    private $direcionEvento;
    private $localitatEvento;
    private $zonaEvento;
    private $confirmacion;
    private $imatgeEvent;
    private $tituloEvent;
    private $subtitolEvent;
    private $data;
    private $imgEvento;

    /**
     *
     * Funcion para generar un documento PDF con diferentes valores según los parametros recibidos
     *
     * @param ref El parámetro ref define la referencia de la entrada
     * @return pdf nos devuelve un documento pdf de la entrada
     *
     */

    public function pdfGenerator($ref)
    {

        require_once __DIR__ . '../../vendor/autoload.php';
        require_once "config/ini-config.php";
        require_once __DIR__ . "../../model/bootstrap.php";

        $mpdf = new \Mpdf\Mpdf();

        /* Si nos devuelve valor... */
        if ($entrada = $entityManager->getRepository("Thos\Entrada")->find($ref)) {

            /* Valores para la Entrada */
            $this->tituloEvent = $entrada->getEvent()->getTitol();
            $this->subtitolEvent = $entrada->getEvent()->getSubtitol();

            $pattern = "/img/";
            $this->imatgeEvent = $entrada->getEvent()->getImatge();

            /* Comprobamos que el valor del string de la
            ruta de la imagen cumpla un criterio*/
            if (preg_match($pattern, $this->imatgeEvent)) {
                $this->imgEvento = '<img class="event-img" src="' . $this->imatgeEvent . '"/>';
            } else {
                $this->imgEvento = '<img class="event-img" src="img/' . $this->imatgeEvent . '"/>';
            }

            $this->fila = $entrada->getFila();
            $this->butaca = $entrada->getButaca();
            $this->dni = $entrada->getCompardor();
            $this->fechaEvento = $entrada->getData()->getData();
            $this->horaEvento = $entrada->getData()->getHora();
            $this->lugarEvento = $entrada->getLocalitzacio()->getLloc();
            $this->direcionEvento = $entrada->getLocalitzacio()->getAdreca();
            $this->localitatEvento = $entrada->getLocalitzacio()->getLocalitat();
            $this->zonaEvento = $entrada->getZona()->getDescripcio();
            $this->confirmacion = $entrada->getPagament()->getReferenciaExterna();
            $this->generateCodeBar($this->confirmacion);
            $this->data = 'http://localhost/M7_Entrada/?ref=' . $ref . '';

            /* Agregamos la marca de agua en el documento */
            $mpdf->SetWatermarkImage('img/pagado.png', 0.2, array(140, 140));
            $mpdf->showWatermarkImage = true;

            /*Agregamos los valores a la entrada */
            $mpdf->WriteHTML($this->generateHTML());

        } else {

            /*Agregamos los valores a la entrada */
            $mpdf->WriteHTML($this->entradaVacia());

        }

        $mpdf->Output();

    }

    /**
     *
     * Funcion para generar una imagen de codigo de barras.
     *
     * @param url El parámetro url define la url de donde descargamos la entrada
     * @return string nos devuelve un html de la imagen del QR
     *
     */
    public function generateQR($url)
    {

        $qr = new QRCode;
        return '<img class="qr-code" src="' . $qr->render($url) . '" alt="QR Code" />';
    }

    /**
     *
     * Funcion para generar una imagen de codigo de barras.
     *
     * @param num El parámetro num define el número de confirmacion de la entrada
     * @return img nos guarda la imagen del codigo de barras
     *
     */
    public function generateCodeBar($num)
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();

        file_put_contents('img/CodeBar.png', $generator->getBarcode($num, $generator::TYPE_CODE_128));

    }

    /**
     *
     * Funcion que nos devuelve el esqueleto de la Entrada
     * con los valores de la entrada con esa ref
     *
     * @return String HTML con los valores para crear el pdf
     */

    public function generateHTML()
    {

        return $htmlEntrada = '
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


            <h1 class="titulo">' . $this->tituloEvent . '</h1>
            <p>' . $this->subtitolEvent . '</p>
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
            <td colspan="2">' . $this->fila . '</td>
            <td colspan="2">' . $this->butaca . '</td>
            <td colspan="2">' . $this->zonaEvento . '</td>


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

            <td colspan="2">' . $this->fechaEvento . '<br> a las ' . $this->horaEvento . '</td>
            <td colspan="2">' . $this->lugarEvento . ' - ' . $this->direcionEvento . '(' . $this->localitatEvento . ')</td>
            <td colspan="2">' . $this->generateQR($this->data) . '</td>

        </tr>

    </tbody>

    <tfoot>
        <tr>
            <td scope="row" colspan="6" class="center-img">
         <div>

            ' . $this->imgEvento . '
            <p class="alert">Enseña el código QR en la entrada para acceder al evento</p>

         </div>
         <b>' . $this->confirmacion . '</b>
            </td>

        </tr>
        <br>
        <img src="img/CodeBar.png"/>


    </tfoot>


 </table>'

        ;

    }

    /**
     *  Funcion que nos devuelve el esqueleto de la
     *  Entrada en caso de que no exista la ref
     *
     * @return String HTML con el esqueleto vacio
     *
     */

    public function entradaVacia()
    {

        return $htmlEntrada = '
        <style media="print">
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
        </style>
        <img class="logo" src="img/atrapalo-logo.png">
        <table>
        <caption>
        <h1 class="titulo"></h1>
        <p></p>
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
            <td colspan="2"></td>
            <td colspan="2"></td>
            <td colspan="2"></td>
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
            <td colspan="2"></td>
            <td colspan="2"></td>
            <td colspan="2"></td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <td scope="row" colspan="6" class="center-img">
                <div>
                </div>
                <b></b>
            </td>
        </tr>
        <br>
        </tfoot>
        </table>
        ';

    }

}
