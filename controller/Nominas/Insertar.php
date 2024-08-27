<?php
include('../../model/ModelMeta.php');
include('../../model/ModelNomina.php');
include('../../model/ModelEmpleado.php');
include('../../model/ModelRuta.php');
/* Variable para llamar al método del modelo */
$modelMeta = new ModelMeta();
$modelNomina = new ModelNomina();
$modelEmpleado = new ModelEmpleado();
$modelRuta = new ModelRuta();
/*function getColorMetaEmpleado($cantidad, $metas)
{
  $color1 = "#FFD6D6";
  $color2 = "#b3d4fc";
  $color3 = "#7fa8f8";
  $color4 = "#5BC8AC";
  $color5 = "#388E3C";
  if ($cantidad < $metas->meta1) return "#DC143C";
  if ($cantidad >= $metas->meta1 && $cantidad < $metas->meta2) return $color1;
  if ($cantidad >= $metas->meta2 && $cantidad < $metas->meta3) return $color2;
  if ($cantidad >= $metas->meta3 && $cantidad < $metas->meta4) return $color3;
  if ($cantidad >= $metas->meta4 && $cantidad < $metas->meta5) return $color4;
  if ($cantidad >= $metas->meta5) return $color5;
}

function calcularComisionEmpleado($jsonComisiones, $valor, $tipoempleado)
{
  $comisionAlcanzada = 0;
  if ($valor < $jsonComisiones->meta1) $comisionAlcanzada = 0;
  if ($valor >= $jsonComisiones->meta1 && $valor < $jsonComisiones->meta2) {
    $comisionAlcanzada = $jsonComisiones->comision1;
  }
  if ($valor >= $jsonComisiones->meta2 && $valor < $jsonComisiones->meta3) {
    $comisionAlcanzada = $jsonComisiones->comision2;
  }
  if ($valor >= $jsonComisiones->meta3 && $valor < $jsonComisiones->meta4) {
    $comisionAlcanzada = $jsonComisiones->comision3;
  }
  if ($valor >= $jsonComisiones->meta4 && $valor < $jsonComisiones->meta5) {
    $comisionAlcanzada = $jsonComisiones->comision4;
  }
  if ($valor >= $jsonComisiones->meta5) {
    $comisionAlcanzada = $jsonComisiones->comision5;
  }
  if ($tipoempleado != 5) {
    return   intval($valor / 1000) * $comisionAlcanzada;
  } else return $valor * $comisionAlcanzada;
}
*/
/*function calcularMetaCumplida($cantidad, $metas)
{
  if ($cantidad < $metas->meta1) 0;
  elseif ($cantidad >=  $metas->meta1 && $cantidad <  $metas->meta2) 1;
  elseif ($cantidad >=  $metas->meta2 && $cantidad <  $metas->meta3) 2;
  elseif ($cantidad >=  $metas->meta3 && $cantidad <  $metas->meta4) 3;
  elseif ($cantidad >=  $metas->meta4 && $cantidad <  $metas->meta5) 4;
  elseif ($cantidad >=  $metas->meta5) 5;
}

function calcularComision($comisiones, $valor, $tipoempleado)
{
  $comisionAlcanzada = 0;
  if ($valor < $comisiones->meta1) $comisionAlcanzada = 0;
  if ($valor >= $comisiones->meta1 && $valor < $comisiones->meta2) {
    $comisionAlcanzada = $comisiones->comision1;
  }
  if ($valor >= $comisiones->meta2 && $valor < $comisiones->meta3) {
    $comisionAlcanzada = $comisiones->comision2;
  }
  if ($valor >= $comisiones->meta3 && $valor < $comisiones->meta4) {
    $comisionAlcanzada = $comisiones->comision3;
  }
  if ($valor >= $comisiones->meta4 && $valor < $comisiones->meta5) {
    $comisionAlcanzada = $comisiones->comision4;
  }
  if ($valor >= $comisiones->meta5) {
    $comisionAlcanzada = $comisiones->comision5;
  }
  if ($tipoempleado != 5) {
    return   intval($valor / 1000) * $comisionAlcanzada;
  } else return $valor * $comisionAlcanzada;
}*/

$fechaInicial = $_POST["fechaInicial"];
$fechaFinal = $_POST["fechaFinal"];
$zonaId = $_POST["zona"];

if (!isset($zonaId) || !isset($fechaInicial) || !isset($fechaFinal)) {
  echo "<script>
        alert('Introduce toda la información');
        window.location.href = '../../view/index.php?action=nominas/index.php';
      </script>";
} else {
  $nominaId = $modelNomina->insertar($zonaId, $fechaInicial, $fechaFinal);
  if ($nominaId) {
    $tiposEmpleados = $modelEmpleado->obtenerTiposEmpleados();
    $metasZona = $modelMeta->obtenerMetasPorZona($zonaId);
    $tipoEmpleadoGerente = null;

    $fechaInicialObj = new DateTime($fechaInicial);
    $fechaFinalObj = new DateTime($fechaFinal);
    // Calcular la diferencia entre las fechas
    $diferencia = $fechaFinalObj->diff($fechaInicialObj);

    // Obtener la diferencia en días para obtener días trabajados
    $diasLaborados = $diferencia->days;

    foreach ($tiposEmpleados as $indiceTipoEmpleado => $tipoEmpleadoData) { //Recorrer todos los tipos de empleados
      //Las personas de anden y oficina tienen salarios fijos 
      // y no usan comisiones para determinar el salario
      // por lo tanto es una tabla diferente
      if ($tipoEmpleadoData["idtipoempleado"] == 4) { //Andén-Oficina
        $nominaTipoEmpleado = $modelEmpleado->obtenerEmpleadosPorTipoZona($zonaId, 4);
      } elseif ($tipoEmpleadoData["idtipoempleado"] == 2) { //Gerente
        $tipoEmpleadoGerente = $tipoEmpleadoData;
        continue; // no mostramos la nomina del gerente porque esta será algo diferente
      } else { //Todos los vendedores
        $nominaTipoEmpleado = $modelNomina->obtenerDatosNominaPorTipoEmpleado($zonaId, $fechaInicial, $fechaFinal, $tipoEmpleadoData["idtipoempleado"]);
      }

      foreach ($nominaTipoEmpleado as $indice => $nominaEmpleado) {
        $rutaId = null;
        $tipoGananciaRutaId = 1;
        $cantidadNormal = 0; //Lts normales vendidos
        $cantidadDescuento = 0;
        $metaCumplidaId = 0;
        $sueldoBaseTotal = 0; //Sueldo por día x días trabajados
        $comisiones = 0;
        $faltas = 0;
        $extras = 0;
        $total = 0;
        $banco = 0;
        $efectivo = 0;

        if ($tipoEmpleadoData["idtipoempleado"] != 4) { //Que no sean los empleados de andén/oficina
          $rutaActual = $modelRuta->obtenerRutaActualEmpleado($nominaEmpleado->idempleado);
          if($rutaActual){
            $rutaId = $rutaActual["idruta"];
            $tipoGananciaRutaId = $rutaActual["tipo_ganancia_ruta_id"];
          }
          
          $cantidadNormal = $nominaEmpleado->normal;

          if ($tipoEmpleadoData["idtipoempleado"] != 5) { //Todos menos los vendedores cilindros
            $cantidadDescuento = $nominaEmpleado->lts_descuento;
          }
          $salarioBaseTotal = $nominaEmpleado->sueldo_base * $diasLaborados;
        }

        //Insertar datos en el histórico de la nómina
        $empleadoNomina = $modelNomina->insertarEmpleadoNomina(
          $nominaId,
          $nominaEmpleado->idempleado,
          $nominaEmpleado->tipo_empleado_id,
          $rutaId,
          $tipoGananciaRutaId,
          $cantidadNormal,
          $cantidadDescuento,
          $metaCumplidaId,
          $diasLaborados,
          $faltas,
          $extras,
          $nominaEmpleado->sueldo_base,
          $sueldoBaseTotal,
          $comisiones,
          $nominaEmpleado->infonavit,
          $total,
          $banco,
          $efectivo
        );
      }
    }

    if ($tipoEmpleadoGerente) {

      $datosEmpleadoGerente = $modelEmpleado->obtenerPrimerEmpleadoPorTipoZona($zonaId, 2); //Gerente
      if ($datosEmpleadoGerente) {
        $rutaId = null;
        $tipoGananciaRutaId = 1;
        $cantidadNormal = 0; //Lts normales vendidos
        $cantidadDescuento = 0;
        $metaCumplidaId = 0;
        $sueldoBaseTotal = 0; //Sueldo por día x días trabajados
        $comisiones = 0;
        $faltas = 0;
        $extras = 0;
        $total = 0;
        $banco = 0;
        $efectivo = 0;

        //Insertar datos en el histórico de la nómina
        $empleadoNomina = $modelNomina->insertarEmpleadoNomina(
          $nominaId,
          $datosEmpleadoGerente->idempleado,
          $datosEmpleadoGerente->tipo_empleado_id,
          $rutaId,
          $tipoGananciaRutaId,
          $cantidadNormal,
          $cantidadDescuento,
          $metaCumplidaId,
          $diasLaborados,
          $faltas,
          $extras,
          $datosEmpleadoGerente->sueldo_base,
          $sueldoBaseTotal,
          $comisiones,
          $datosEmpleadoGerente->infonavit,
          $total,
          $banco,
          $efectivo
        );
      }
    }
    echo "<script>
      window.location.href = '../../view/index.php?action=nominas/detalles.php&id=" . $nominaId . "';
    </script>";
  } else {
    echo "<script>
        alert('Error al insertar la nueva nómina. Introduce toda tu información para guardarla');
        window.location.href = '../../view/index.php?action=nominas/index.php';
      </script>";
  }
}
