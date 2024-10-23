<?php
include_once('Medoo.php');

use Medoo\Medoo;
require_once __DIR__ . '/../vendor/autoload.php';
/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelDonacion
{

  var $base_datos; //Variable para hacer la conexion a la base de datos
  var $resultado; //Variable para traer resultados de una consulta a la BD

  function __construct()
  { //Constructor de la conexion a la BD
    $this->base_datos = new Medoo();
  }

  function obtenerDonacionesFecha($fecha)
  {
    $sql = $this->base_datos->query("SELECT * FROM donaciones 
    WHERE fecha = '$fecha' ORDER BY fecha DESC")->fetchAll(PDO::FETCH_ASSOC);
    return $sql;
  }

  function obtenerDonacionesZonaEntreFechas($fechaInicial, $fechaFinal, $zonaId)
  {
    $sql = $this->base_datos->query("SELECT * FROM donaciones 
    WHERE zona_id='$zonaId' AND fecha >= '$fechaInicial' AND fecha <= '$fechaFinal' 
    ORDER BY fecha DESC")->fetchAll(PDO::FETCH_ASSOC);
    return $sql;
  }

  function insertar($fecha, $cantidad, $zonaId, $comentario, $comprobanteDonacion)
  {
    $this->base_datos->insert("donaciones", [
      "fecha" => $fecha,
      "kilogramos" => $cantidad,
      "zona_id" => $zonaId,
      "comentarios" => $comentario,
      "comprobante_donaciones" => $comprobanteDonacion
    ]);
    return $this->base_datos->id();
  }

  function eliminar($id)
  {
    $this->base_datos->delete("donaciones", ["iddonacion[=]" => $id]);
  }

  function crearPdfDonacionesZonaEntreFechas($fechaInicial, $fechaFinal, $zonaId, $zonaNombre)
  {
    $filename = "ReporteDonaciones-" . $zonaNombre . " De " . $fechaInicial . " A " . $fechaFinal . ".pdf";

    $donaciones = $this->base_datos->query("SELECT * FROM donaciones 
    WHERE (fecha >= '$fechaInicial' AND fecha <= '$fechaFinal' AND zona_id = '$zonaId') ORDER BY fecha ASC");

    $mpdf = new \Mpdf\Mpdf();

    $html = '
      <style>
      table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
      }
      .title {
        border: 0px;
        border-collapse: collapse;
      }
      </style>
        <div style="text-align: left; font-weight: bold;">
          <table class="title" style="width:100%"> 
            <tr class="title">
              <td class="title"><img src="../../images/logo.png" width="150" /></td>
              <td class="title" style="text-align: center; font-weight: bold;">
                <h3>Reporte de Donaciones ' . '' . '</h3>
                <p>Fecha: ' . $fechaInicial . ' A ' . $fechaFinal . '</p>
                <p>Zona: ' . $zonaNombre . '</p>
              </td>
            </tr>
          </table>
          <br>
      </div>';

    $html = $html . '<table style="width:100%; border: 1px solid black; border-collapse: collapse;" autosize="2">
                <tr>
                   <th> Fecha </th>
                   <th> Kilogramos </th>
                   <th> Zona </th>
                   <th> Comentarios </th>
                   </tr>';
    $totalKg = 0;
    foreach ($donaciones as $donacion) {
      $totalKg = $totalKg + $donacion["kilogramos"];
      $html = $html . "<tr>
                          <td> " . $donacion["fecha"] . "</td>
                          <td> " . $donacion["kilogramos"] . "</td>
                          <td> " . $donacion["zona"] . "</td>
                          <td> " . $donacion["comentarios"] . "</td>
                          </tr>";
    }
    $html = $html . "<tr><th><font size='4'>Total</th><td>" . number_format(($totalKg), 2) . "</td><td></td><td></td></table>";

    $mpdf->WriteHTML($html);
    $mpdf->Output($filename, "D");
  }
}
