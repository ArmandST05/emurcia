<?php
$modelZona = new ModelZona();
$modelNomina = new ModelNomina();
$modelEmpleado = new ModelEmpleado();
$modelMeta = new ModelMeta();
$modelRuta = new ModelRuta();

$nominaId = isset($_GET["id"]) ? $_GET["id"] : "";
if ($nominaId) {
  $nomina = $modelNomina->obtenerNominaId($nominaId);
  if (!$nomina) {
    echo "<script> 
        alert('Esta nómina no existe.');
        window.location.href = 'index.php?action=nominas/index.php';
      </script>";
  }
} else {
  echo "<script> 
    alert('Esta nómina no existe.');
    window.location.href = 'index.php?action=nominas/index.php';
  </script>";
}
$zonaId = $nomina->zona_id;
$fechaInicial = $nomina->fecha_inicio;
$fechaFinal = $nomina->fecha_fin;

$color1 = "#FFD6D6";
$color2 = "#b3d4fc";
$color3 = "#7fa8f8";
$color4 = "#5BC8AC";
$color5 = "#388E3C";

$tiposEmpleados = $modelNomina->obtenerTiposEmpleadosNomina($nominaId);
$metasZona = $modelMeta->obtenerMetasPorZona($zonaId);
$tiposGananciaRuta = $modelRuta->listaTiposGananciaRutaTodos();
$tipoEmpleadoGerente = null;

$fechaInicialObj = new DateTime($fechaInicial);
$fechaFinalObj = new DateTime($fechaFinal);
// Calcular la diferencia entre las fechas
$diferencia = $fechaFinalObj->diff($fechaInicialObj);

// Obtener la diferencia en días para obtener días trabajados
$diasTrabajados = $diferencia->days + 1;

$metasEmpleados = []; //Array para guardar todas las metas por tipo de empleado a utilizar
$metasGerente = [];
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="index.php?action=nominas/index.php">Nómina</a> /
    <a href="#">Detalles</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Detalles nómina</h1>
</div>
<!-- Inicio nómina por tipos de empleado -->
<?php if ($nomina) : ?>
  <div class="row" id="tablaContent">
    <div class="col-xl-12 col-lg-12">
      <div class="card shadow mb-4">
        <!-- Card Header -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Nómina por empleados</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
          <div class="row">
            <div class="col-md-2 offset-md-10">
              <button class="btn btn-sm btn-warning" id="btnExport"><i class="far fa-file-excel"></i> Exportar Excel</button>
            </div>
          </div>
          <div class="row" id="listaTabla">
            <div class="col-md-12">
              <table class="table table-bordered table-sm table-responsive" style="width:100%; font-size: .80rem; ">
                <thead>
                  <tr>
                    <th colspan="7">Fecha inicio: <?php echo $fechaInicial ?></th>
                    <th colspan="7">Fecha fin: <?php echo $fechaFinal ?></th>
                  </tr>
                </thead>
                <?php
                foreach ($tiposEmpleados as $indiceTipoEmpleado => $tipoEmpleadoData) :
                  $tipoEmpleadoId = $tipoEmpleadoData->idtipoempleado;

                  foreach ($tiposGananciaRuta as $tipoGananciaRuta) :
                    $tipoGananciaId = $tipoGananciaRuta["idtipogananciaruta"];

                    if ($tipoEmpleadoId == 6) { //Vendedor estación
                      $rutas = $modelNomina->obtenerRutasPorTipoNomina($nominaId, 5);
                    } else {
                      $rutas = array(
                        (object) [
                          'idruta' => 0,
                          'clave_ruta' => ""
                        ]
                      );
                    }

                    foreach ($rutas as $ruta) : //Para en el caso de las estaciones mostrar cada estación
                      $rutaId = $ruta->idruta;
                      $rutaNombre = $ruta->clave_ruta;
                      //Las personas de anden y oficina tienen salarios fijos y no usan comisiones, es una tabla diferente.
                      if ($tipoEmpleadoId == 2) { //Gerente
                        $tipoEmpleadoGerente = $tipoEmpleadoData;
                        continue; // no mostramos la nomina del gerente porque esta será algo diferente
                      } else { //Todos los vendedores
                        $nominaTipoEmpleado = $modelNomina->obtenerEmpleadosNominaTipo($nominaId, $tipoEmpleadoId, $tipoGananciaRuta["idtipogananciaruta"], $rutaId);
                      }

                      $metasPorEmpleados = $modelMeta->obtenerMetasTipoEmpleado($zonaId, $tipoEmpleadoId, $tipoGananciaId, 0, $rutaId);
                      $metasEmpleados["tipoEmpleado" . $tipoEmpleadoId]["tipoGanancia" . $tipoGananciaId]["tipoDescuento0"]["rutaId" . $rutaId] = (array)$metasPorEmpleados;

                      $metasPorEmpleadosDescuento = $modelMeta->obtenerMetasTipoEmpleado($zonaId, $tipoEmpleadoId, $tipoGananciaId, 1, $rutaId);
                      $metasEmpleados["tipoEmpleado" . $tipoEmpleadoId]["tipoGanancia" . $tipoGananciaId]["tipoDescuento1"]["rutaId" . $rutaId] = (array)$metasPorEmpleadosDescuento;

                      if ($nominaTipoEmpleado) :
                ?>
                        <thead style="color: #000000 ">
                          <tr>
                            <th colspan="15" bgcolor="lightgray"><?php echo $tipoEmpleadoData->nombre . " " . strtoupper($rutaNombre) ?></th>
                          </tr>
                          <?php if ($tipoEmpleadoId != 4 && $tipoEmpleadoId != 6) : ?>
                            <tr>
                              <th colspan="15"><?php echo $tipoGananciaRuta["nombre"] ?></th>
                            </tr>
                          <?php endif; ?>
                          <?php if ($metasPorEmpleados) : ?>

                            <!-- Validar si es cilindrera o por litro para poner las metas como corresponde -->
                            <?php
                            if ($tipoEmpleadoId != 5) : //Vendedores pipas
                            ?>
                              <tr>
                                <td colspan="2">
                                  Metas normal (litros)
                                </td>
                                <td style="background-color: <?php echo $color5 ?>; color:#000000;"><?php echo ($metasPorEmpleados->meta5 / 1000) ?> mil</td>
                                <td style="background-color: <?php echo $color4 ?>; color:#000000;"><?php echo ($metasPorEmpleados->meta4 / 1000) ?> mil</td>
                                <td style="background-color: <?php echo $color3 ?>; color:#000000;"><?php echo ($metasPorEmpleados->meta3 / 1000) ?> mil</td>
                                <td style="background-color: <?php echo $color2 ?>; color:#000000;"><?php echo ($metasPorEmpleados->meta2 / 1000) ?> mil</td>
                                <td style="background-color: <?php echo $color1 ?>; color:#000000;"><?php echo ($metasPorEmpleados->meta1 / 1000) ?> mil</td>
                              </tr>
                            <?php else : //Cilindreros
                            ?>
                              <tr>
                                <td colspan="2">
                                  Metas normal (Cilindros)
                                </td>
                                <td style="background-color: <?php echo $color5 ?>; color:#000000;"><?php echo ($metasPorEmpleados->meta5) ?> Cilindros</td>
                                <td style="background-color: <?php echo $color4 ?>; color:#000000;"><?php echo ($metasPorEmpleados->meta4) ?> Cilindros</td>
                                <td style="background-color: <?php echo $color3 ?>; color:#000000;"><?php echo ($metasPorEmpleados->meta3) ?> Cilindros</td>
                                <td style="background-color: <?php echo $color2 ?>; color:#000000;"><?php echo ($metasPorEmpleados->meta2) ?> Cilindros</td>
                                <td style="background-color: <?php echo $color1 ?>; color:#000000;"><?php echo ($metasPorEmpleados->meta1) ?> Cilindros</td>
                              </tr>
                            <?php endif; ?>
                            <tr>
                              <td colspan="2">Comisiones normal</td>
                              <td>
                                <?php echo ($metasPorEmpleados->comision5) ?>
                              </td>
                              <td>
                                <?php echo ($metasPorEmpleados->comision4) ?>
                              </td>
                              <td>
                                <?php echo ($metasPorEmpleados->comision3) ?>
                              </td>
                              <td>
                                <?php echo ($metasPorEmpleados->comision2) ?>
                              </td>
                              <td>
                                <?php echo ($metasPorEmpleados->comision1) ?>
                              </td>
                            </tr>
                          <?php endif; ?>
                          <?php if ($metasPorEmpleadosDescuento) : ?>

                            <!-- Validar si es cilindrera o por litro para poner las metas como corresponde -->
                            <?php
                            if ($tipoEmpleadoId != 5) : //Vendedores pipas
                            ?>
                              <tr>
                                <td colspan="2">
                                  Metas descuento (litros)
                                </td>
                                <td style="background-color: <?php echo $color5 ?>; color:#000000;"><?php echo ($metasPorEmpleadosDescuento->meta5 / 1000) ?> mil</td>
                                <td style="background-color: <?php echo $color4 ?>; color:#000000;"><?php echo ($metasPorEmpleadosDescuento->meta4 / 1000) ?> mil</td>
                                <td style="background-color: <?php echo $color3 ?>; color:#000000;"><?php echo ($metasPorEmpleadosDescuento->meta3 / 1000) ?> mil</td>
                                <td style="background-color: <?php echo $color2 ?>; color:#000000;"><?php echo ($metasPorEmpleadosDescuento->meta2 / 1000) ?> mil</td>
                                <td style="background-color: <?php echo $color1 ?>; color:#000000;"><?php echo ($metasPorEmpleadosDescuento->meta1 / 1000) ?> mil</td>
                              </tr>
                            <?php else : //Cilindreros
                            ?>
                              <tr>
                                <td colspan="2">
                                  Metas descuento (Cilindros)
                                </td>
                                <td style="background-color: <?php echo $color5 ?>; color:#000000;"><?php echo ($metasPorEmpleadosDescuento->meta5) ?> Cilindros</td>
                                <td style="background-color: <?php echo $color4 ?>; color:#000000;"><?php echo ($metasPorEmpleadosDescuento->meta4) ?> Cilindros</td>
                                <td style="background-color: <?php echo $color3 ?>; color:#000000;"><?php echo ($metasPorEmpleadosDescuento->meta3) ?> Cilindros</td>
                                <td style="background-color: <?php echo $color2 ?>; color:#000000;"><?php echo ($metasPorEmpleadosDescuento->meta2) ?> Cilindros</td>
                                <td style="background-color: <?php echo $color1 ?>; color:#000000;"><?php echo ($metasPorEmpleadosDescuento->meta1) ?> Cilindros</td>
                              </tr>
                            <?php endif; ?>
                            <tr>
                              <td colspan="2">Comisiones descuento</td>
                              <td>
                                <?php echo ($metasPorEmpleadosDescuento->comision5) ?>
                              </td>
                              <td>
                                <?php echo ($metasPorEmpleadosDescuento->comision4) ?>
                              </td>
                              <td>
                                <?php echo ($metasPorEmpleadosDescuento->comision3) ?>
                              </td>
                              <td>
                                <?php echo ($metasPorEmpleadosDescuento->comision2) ?>
                              </td>
                              <td>
                                <?php echo ($metasPorEmpleadosDescuento->comision1) ?>
                              </td>
                            </tr>
                          <?php endif; ?>
                        </thead>
                        <thead>
                          <tr>
                            <th>Ruta</th>
                            <th>Nombre</th>
                            <?php if ($tipoEmpleadoId != 4) : //No son empleados planta
                              if ($tipoEmpleadoId == 5) : //Son cilindreras
                            ?>
                                <th>Cilindros</th>
                                <th>$ Comisión</th>
                                <th></th>
                                <th></th>
                              <?php else : ?>
                                <th>Lts Precio Normal</th>
                                <th>$ Comisión precio normal </th>
                                <th>Lts Descuento/Crédito</th>
                                <th>$ Comisión precio descuento </th>
                              <?php endif; ?>
                              <th>Sueldo base diario</th>
                            <?php else : ?>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                            <?php endif; ?>
                            <th>Infonavit</th>
                            <th>Faltas $</th>
                            <th>Sueldo base</th>
                            <th>Extras</th>
                            <th>Total</th>
                            <th>Banco</th>
                            <th>Efectivo</th>
                            <th>Fondo</th>
                            <th>Observaciones</th>
                          </tr>
                        </thead>
                        <tbody style="color:#000000;">
                          <?php
                          foreach ($nominaTipoEmpleado as $indice => $nominaEmpleado) :
                            $empleadoId = $nominaEmpleado->empleado_id;
                            $ruta = $modelRuta->obtenerRutaId($nominaEmpleado->ruta_id);

                            $datosVentaEmpleado = $modelNomina->obtenerDatosNominaVentaPorEmpleado($zonaId, $fechaInicial, $fechaFinal, $empleadoId);

                            if($tipoEmpleadoId == 5){//Cilindros
                              $cantidadNormal = floatval($datosVentaEmpleado->total_cilindros);
                            }else if($tipoEmpleadoId == 6){//Estaciones
                              $cantidadNormal = floatval($datosVentaEmpleado->total_lts_normal)+floatval($datosVentaEmpleado->lts_descuento_credito);
                            }else{
                              $cantidadNormal = floatval($datosVentaEmpleado->total_lts_normal);
                            }
                          ?>
                            <tr class="tr-te<?php echo $tipoEmpleadoId ?>" data-empleado-id="<?php echo $empleadoId ?>" data-tipo-empleado-id="<?php echo $tipoEmpleadoId ?>" data-tipo-ganancia-id="<?php echo $tipoGananciaId ?>" data-ruta-id="<?php echo $rutaId ?>">
                              <td><?php echo ($ruta) ? $ruta["clave_ruta"] : "" ?></td>
                              <td><?php echo $nominaEmpleado->nombre ?></td>

                              <?php if ($tipoEmpleadoId != 4) : //Que no sean los empleados de andén/oficina
                              ?>
                                <td id="e<?php echo $empleadoId ?>cantidad_normal" data-columna-nombre="cantidad_normal" style="color:#000000;"><?php echo number_format($cantidadNormal, 2, '.', ',') ?></td>
                                <td id="e<?php echo $empleadoId ?>comisiones" data-columna-nombre="comisiones"><?php echo number_format($nominaEmpleado->comisiones, 2, '.', ',') ?></td>
                                <?php
                                // Los vendedores de cilindro no necesitan campo descuento, solo las pipas.
                                if ($tipoEmpleadoId != 5 && $tipoEmpleadoId != 6) : //No es Cilindro, ni Estación (Las estaciones solamente tienen 1 comisión normal)
                                ?>
                                  <td id="e<?php echo $empleadoId ?>cantidad_descuento" data-columna-nombre="cantidad_descuento"><?php echo number_format(floatval($datosVentaEmpleado->lts_descuento_credito), 2, '.', ',') ?></td>
                                  <td id="e<?php echo $empleadoId ?>comisiones_descuento" data-columna-nombre="comisiones_descuento"><?php echo number_format($nominaEmpleado->comisiones_descuento, 2, '.', ',') ?></td>
                                <?php else : ?>
                                  <td></td>
                                  <td></td>
                                <?php endif; ?>
                                <td id="e<?php echo $empleadoId ?>sueldo_base_dia" data-columna-nombre="sueldo_base_dia" class="editValueEmployee"><?php echo number_format($nominaEmpleado->sueldo_base_dia, 2, '.', ',') ?></td>
                              <?php else : ?>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                              <?php endif; ?>
                              <td id="e<?php echo $empleadoId ?>infonavit" data-columna-nombre="infonavit" class="editValueEmployee"><?php echo number_format($nominaEmpleado->infonavit, 2, '.', ',') ?></td>
                              <td id="e<?php echo $empleadoId ?>faltas" data-columna-nombre="faltas" class="editValueEmployee" data-columna-nombre="faltas" class="editValueEmployee"><?php echo number_format($nominaEmpleado->faltas, 2, '.', ',') ?></td>
                              <td id="e<?php echo $empleadoId ?>sueldo_base_total" data-columna-nombre="sueldo_base_total" class="<?php echo ($tipoEmpleadoId == 4) ? 'editValueEmployee' : '' ?>"><?php echo ($tipoEmpleadoId != 4) ? number_format(($nominaEmpleado->sueldo_base_dia * $diasTrabajados), 2, '.', ',') : number_format($nominaEmpleado->sueldo_base_dia, 2, '.', ',') ?></td>
                              <td id="e<?php echo $empleadoId ?>extras" data-columna-nombre="extras" class="editValueEmployee"><?php echo number_format($nominaEmpleado->extras, 2, '.', ',') ?></td>
                              <td id="e<?php echo $empleadoId ?>total" data-columna-nombre="total" class="empleado-total"><?php echo number_format($nominaEmpleado->total, 2, '.', ',') ?></td>
                              <td id="e<?php echo $empleadoId ?>banco" data-columna-nombre="banco" class="editValueEmployee empleado-banco"><?php echo number_format($nominaEmpleado->banco, 2, '.', ',') ?></td>
                              <td id="e<?php echo $empleadoId ?>efectivo" data-columna-nombre="efectivo" class="empleado-efectivo"><?php echo number_format($nominaEmpleado->efectivo, 2, '.', ',') ?></td>
                              <td id="e<?php echo $empleadoId ?>fondo" data-columna-nombre="fondo" class="gerente-fondo editValueEmployee">
                                  <?php echo number_format($nominaEmpleado->fondo, 2, '.', ',') ?>
                              </td>
                              <td id="e<?php echo $empleadoId ?>observaciones" data-columna-nombre="observaciones" class="editValueEmployee"><?php echo $nominaEmpleado->observaciones ?></td>
                            </tr>
                          <?php endforeach; ?>

                        </tbody>
                <?php endif;
                    endforeach;
                  endforeach;
                endforeach; ?>
                <!-- **************************************NÓMINA GERENTE********************************************************************* -->
                <?php if ($tipoEmpleadoGerente) :
                  $tipoEmpleadoId = $tipoEmpleadoGerente->idtipoempleado;
                  $metasPorEmpleados = $modelMeta->obtenerMetasTipoEmpleado($zonaId, $tipoEmpleadoId, 1, 0, 0);
                  $datosEmpleadoGerente = $modelNomina->obtenerEmpleadosNominaTipo($nominaId, $tipoEmpleadoId, 0, null, 0);
                  $empleadoId = $datosEmpleadoGerente->empleado_id;

                  if ($datosEmpleadoGerente) :
                ?>
                    <thead style="color: #000000 ">
                      <tr>
                        <th colspan="15" bgcolor="lightgray"><?php echo $tipoEmpleadoGerente->nombre ?></th>
                      </tr>
                      <tr>
                        <th colspan="2">Nombre</th>
                        <th colspan="4"></th>
                        <th>Comisiones</th>
                        <th>Infonavit</th>
                        <th>Faltas</th>
                        <th>Sueldo base</th>
                        <th>Extras</th>
                        <th>Total</th>
                        <th>Banco</th>
                        <th>Efectivo</th>
                        <th>Fondo</th>
                        <th>Observaciones</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr id="gerente-detalle" data-empleado-id="<?php echo $empleadoId ?>" data-tipo-empleado-id="<?php echo $tipoEmpleadoId ?>">
                        <td colspan="2"><?php echo $datosEmpleadoGerente->nombre ?></td>
                        <td colspan="4"></td>
                        <td id="e<?php echo $empleadoId ?>comisiones" data-columna-nombre="comisiones" class="gerente-comisiones editValueEmployee"><?php echo ((isset($datosEmpleadoGerente->comisiones)) ? $datosEmpleadoGerente->comisiones : 0) ?></td>
                        <td id="e<?php echo $empleadoId ?>infonavit" data-columna-nombre="infonavit" class="gerente-infonavit editValueEmployee"><?php echo number_format($datosEmpleadoGerente->infonavit, 2, '.', ',') ?></td>
                        <td id="e<?php echo $empleadoId ?>faltas" data-columna-nombre="faltas" class="gerente-faltas editValueEmployee"><?php echo number_format($datosEmpleadoGerente->faltas, 2, '.', ',') ?></td>
                        <td id="e<?php echo $empleadoId ?>sueldo_base_total" data-columna-nombre="sueldo_base_total" class="gerente-sueldo_base_total editValueEmployee"><?php echo ($datosEmpleadoGerente->sueldo_base_dia) ?></td>
                        <td id="e<?php echo $empleadoId ?>extras" data-columna-nombre="extras" class="gerente-extras editValueEmployee"><?php echo number_format($datosEmpleadoGerente->extras, 2, '.', ',') ?></td>
                        <td id="e<?php echo $empleadoId ?>total" data-columna-nombre="total" class="gerente-total empleado-total"><?php echo number_format($datosEmpleadoGerente->total, 2, '.', ',') ?></td>
                        <td id="e<?php echo $empleadoId ?>banco" data-columna-nombre="banco" class="gerente-banco editValueEmployee empleado-banco"><?php echo number_format($datosEmpleadoGerente->banco, 2, '.', ',') ?></td>
                        <td id="e<?php echo $empleadoId ?>efectivo" data-columna-nombre="efectivo" class="gerente-efectivo empleado-efectivo"><?php echo number_format($datosEmpleadoGerente->efectivo, 2, '.', ',') ?></td>
                        <td id="e<?php echo $empleadoId ?>fondo" data-columna-nombre="fondo" class="gerente-fondo editValueEmployee"><?php echo number_format($datosEmpleadoGerente->fondo, 2, '.', ',') ?></td>

                        <td id="e<?php echo $empleadoId ?>observaciones" data-columna-nombre="observaciones" class="gerente-observaciones editValueEmployee"><?php echo $datosEmpleadoGerente->observaciones ?></td>
                      </tr>
                    </tbody>
                    <?php if ($metasPorEmpleados) :
                      foreach ($metasPorEmpleados as $metaGerente) :
                        $metaId = $metaGerente->idmetazona;
                        $ambitosMeta = $modelMeta->obtenerMetaGerente($metaId);
                        $detallesMeta["data"] = $metaGerente;
                        $detallesMeta["ambitos"] = $ambitosMeta;
                        $metasGerente["metaId" . $metaId] = $detallesMeta;

                        $metaCilindros = false;

                        foreach ($ambitosMeta as $ambito) {
                          if ($ambito->ambito_comision_id == 4) {
                            $metaCilindros = true;
                            break;
                          }
                        }
                    ?>
                        <tbody>
                          <!-- Validar si es cilindrera o por litro el tipo de meta -->
                          <tr>
                            <td>
                              Meta: <?php echo $metaGerente->nombre ?>
                            </td>
                            <?php if ($metaCilindros) : ?>
                              <td style="background-color: <?php echo $color5 ?>; color:#000000;"><?php echo ($metaGerente->meta5) ?> Cilindros</td>
                              <td style="background-color: <?php echo $color4 ?>; color:#000000;"><?php echo ($metaGerente->meta4) ?> Cilindros</td>
                              <td style="background-color: <?php echo $color3 ?>; color:#000000;"><?php echo ($metaGerente->meta3) ?> Cilindros</td>
                              <td style="background-color: <?php echo $color2 ?>; color:#000000;"><?php echo ($metaGerente->meta2) ?> Cilindros</td>
                              <td style="background-color: <?php echo $color1 ?>; color:#000000;"><?php echo ($metaGerente->meta1) ?> Cilindros</td>
                            <?php else : ?>
                              <td style="background-color: <?php echo $color5 ?>; color:#000000;"><?php echo ($metaGerente->meta5 / 1000) ?> mil</td>
                              <td style="background-color: <?php echo $color4 ?>; color:#000000;"><?php echo ($metaGerente->meta4 / 1000) ?> mil</td>
                              <td style="background-color: <?php echo $color3 ?>; color:#000000;"><?php echo ($metaGerente->meta3 / 1000) ?> mil</td>
                              <td style="background-color: <?php echo $color2 ?>; color:#000000;"><?php echo ($metaGerente->meta2 / 1000) ?> mil</td>
                              <td style="background-color: <?php echo $color1 ?>; color:#000000;"><?php echo ($metaGerente->meta1 / 1000) ?> mil</td>
                            <?php endif; ?>
                            <td colspan="9"></td>
                          </tr>
                          <tr>
                            <td>Comisiones</td>
                            <td>
                              <?php echo ($metaGerente->comision5) ?>
                            </td>
                            <td>
                              <?php echo ($metaGerente->comision4) ?>
                            </td>
                            <td>
                              <?php echo ($metaGerente->comision3) ?>
                            </td>
                            <td>
                              <?php echo ($metaGerente->comision2) ?>
                            </td>
                            <td>
                              <?php echo ($metaGerente->comision1) ?>
                            </td>
                            <td colspan="9"></td>
                          </tr>
                        </tbody>
                        <thead>
                          <tr>
                            <th colspan="5"></th>
                            <th>Cantidad meta</th>
                            <th>Comisión meta</th>
                            <th colspan="8"></th>
                        </thead>
                        <tbody>
                          <tr data-empleado-id="<?php echo $empleadoId ?>">
                            <td colspan="5"></td>
                            <td id="gerente<?php echo $metaId ?>cantidadNormal" style="color:#000000;">0.00</td>
                            <td id="gerente<?php echo $metaId ?>comisiones" data-columna-nombre="comisiones">0.00</td>
                            <td colspan="8"></td>
                          </tr>
                          <tr>
                            <td colspan="15"><br></td>
                          </tr>
                        </tbody>
                      <?php endforeach; ?>
                <?php endif;
                  endif;
                endif; ?>
                <thead>
                  <tr bgcolor="lightgray">
                    <th colspan="11"></th>
                    <th>TOTAL</th>
                    <th>BANCO</th>
                    <th>EFECTIVO</th>
                    <th></th>
                </thead>
                <tbody>
                  <tr>
                    <td colspan="11"></td>
                    <td id="nominaTotal"><?php echo number_format($nomina->total, 2) ?></td>
                    <td id="nominaBanco"><?php echo number_format($nomina->banco, 2) ?></td>
                    <td id="nominaEfectivo"><?php echo number_format($nomina->efectivo, 2) ?></td>
                    <td id="nominaObservaciones"><?php echo $nomina->observaciones ?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="row">
            <div class="col-md-1 offset-md-11">
              <button type="submit" class="btn btn-primary btn-sm" id="save" onclick="save()">Guardar</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>
<!-- Fin nómina por tipos de empleado -->
<script>


  var metasEmpleados = <?php echo json_encode($metasEmpleados) ?>;
  var metasGerente = <?php echo json_encode($metasGerente) ?>;

  $(document).ready(function() {
    // Esta función se ejecutará cuando el documento esté listo

    $("#btnExport").click(function(e) {
      $("#listaTabla").btechco_excelexport({
        containerid: "listaTabla",
        datatype: $datatype.Table,
        filename: 'NOMINA ' + "<?php echo $nomina->zona_nombre ?>"
      });
    });

    // Iterar sobre cada fila en la tabla
    $('tbody tr').each(function(index) {
      // Encontrar los elementos de input en la fila actual
      calcularTotalesEmpleado($(this));
    });

  });

  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
  });

  function calcularComisionEmpleado(tipoEmpleadoId, tipoGananciaId, tipoDescuento, rutaId, valor) {
    //Tipos descuento 0-Normal sin descuento 1 =ConDescuento
    if (metasEmpleados["tipoEmpleado" + tipoEmpleadoId] && metasEmpleados["tipoEmpleado" + tipoEmpleadoId]["tipoGanancia" + tipoGananciaId] &&
      metasEmpleados["tipoEmpleado" + tipoEmpleadoId]["tipoGanancia" + tipoGananciaId]["tipoDescuento" + tipoDescuento] &&
      metasEmpleados["tipoEmpleado" + tipoEmpleadoId]["tipoGanancia" + tipoGananciaId]["tipoDescuento" + tipoDescuento]["rutaId" + rutaId]) {
      let metaValidar = metasEmpleados["tipoEmpleado" + tipoEmpleadoId]["tipoGanancia" + tipoGananciaId]["tipoDescuento" + tipoDescuento]["rutaId" + rutaId];
      var comisionAlcanzada = 0;

      if (valor < metaValidar.meta1) {
        comisionAlcanzada = 0;
      } else if (valor >= metaValidar.meta1 && valor < metaValidar.meta2) {
        comisionAlcanzada = metaValidar.comision1;
      } else if (valor >= metaValidar.meta2 && valor < metaValidar.meta3) {
        comisionAlcanzada = metaValidar.comision2;
      } else if (valor >= metaValidar.meta3 && valor < metaValidar.meta4) {
        comisionAlcanzada = metaValidar.comision3;
      } else if (valor >= metaValidar.meta4 && valor < metaValidar.meta5) {
        comisionAlcanzada = metaValidar.comision4;
      } else if (valor >= metaValidar.meta5) {
        comisionAlcanzada = metaValidar.comision5;
      }
      if (tipoEmpleadoId == 5) { //Cilindros
        return valor * comisionAlcanzada
      }else{
        return Math.round((valor / 1000) * comisionAlcanzada);
      }
    } else {
      return 0;
    }

  }

  function getColorMetaEmpleado(tipoEmpleadoId, tipoGananciaId, tipoDescuento, rutaId, valor) {
    if (metasEmpleados["tipoEmpleado" + tipoEmpleadoId] && metasEmpleados["tipoEmpleado" + tipoEmpleadoId]["tipoGanancia" + tipoGananciaId] &&
      metasEmpleados["tipoEmpleado" + tipoEmpleadoId]["tipoGanancia" + tipoGananciaId]["tipoDescuento" + tipoDescuento] &&
      metasEmpleados["tipoEmpleado" + tipoEmpleadoId]["tipoGanancia" + tipoGananciaId]["tipoDescuento" + tipoDescuento]["rutaId" + rutaId]) {
      let metaValidar = metasEmpleados["tipoEmpleado" + tipoEmpleadoId]["tipoGanancia" + tipoGananciaId]["tipoDescuento" + tipoDescuento]["rutaId" + rutaId];

      //Tipos descuento 0-Normal sin descuento 1 =ConDescuento
      const color1 = "#FFD6D6";
      const color2 = "#b3d4fc";
      const color3 = "#7fa8f8";
      const color4 = "#5BC8AC";
      const color5 = "#388E3C";

      if (valor < metaValidar.meta1) return "#DC143C";
      if (valor >= metaValidar.meta1 && valor < metaValidar.meta2) return color1;
      if (valor >= metaValidar.meta2 && valor < metaValidar.meta3) return color2;
      if (valor >= metaValidar.meta3 && valor < metaValidar.meta4) return color3;
      if (valor >= metaValidar.meta4 && valor < metaValidar.meta5) return color4;
      if (valor >= metaValidar.meta5) return color5;
    } else {
      return "#FFFFFF"
    }
  }

  function calcularComisionGerente(metaId, valor) {
    if (metasGerente["metaId" + metaId] && metasGerente["metaId" + metaId]["data"]) {
      let metaValidar = metasGerente["metaId" + metaId]["data"];
      var comisionAlcanzada = 0;

      if (valor < metaValidar.meta1) {
        comisionAlcanzada = 0;
      } else if (valor >= metaValidar.meta1 && valor < metaValidar.meta2) {
        comisionAlcanzada = metaValidar.comision1;
      } else if (valor >= metaValidar.meta2 && valor < metaValidar.meta3) {
        comisionAlcanzada = metaValidar.comision2;
      } else if (valor >= metaValidar.meta3 && valor < metaValidar.meta4) {
        comisionAlcanzada = metaValidar.comision3;
      } else if (valor >= metaValidar.meta4 && valor < metaValidar.meta5) {
        comisionAlcanzada = metaValidar.comision4;
      } else if (valor >= metaValidar.meta5) {
        comisionAlcanzada = metaValidar.comision5;
      }
      if (metaValidar.idtipoempleado === 5) { //Cilindros
        return valor * comisionAlcanzada
      }
      //Se redondeará si es mayor o igual a .5
      return Math.round(valor / 1000) * comisionAlcanzada;
    } else {
      return 0;
    }
  }

  function getColorMetaGerente(metaId, valor) {
    if (metasGerente["metaId" + metaId] && metasGerente["metaId" + metaId]["data"]) {
      let metaValidar = metasGerente["metaId" + metaId]["data"];

      //Tipos descuento 0-Normal sin descuento 1 =ConDescuento
      const color1 = "#FFD6D6";
      const color2 = "#b3d4fc";
      const color3 = "#7fa8f8";
      const color4 = "#5BC8AC";
      const color5 = "#388E3C";

      if (valor < metaValidar.meta1) return "#DC143C";
      if (valor >= metaValidar.meta1 && valor < metaValidar.meta2) return color1;
      if (valor >= metaValidar.meta2 && valor < metaValidar.meta3) return color2;
      if (valor >= metaValidar.meta3 && valor < metaValidar.meta4) return color3;
      if (valor >= metaValidar.meta4 && valor < metaValidar.meta5) return color4;
      if (valor >= metaValidar.meta5) return color5;
    } else {
      return "#FFFFFF"
    }
  }

  $('td.editValueEmployee').click(function() {
    let tdEditado = $(this);
    let trEmpleado = tdEditado.closest('tr');
    let empleadoId = trEmpleado.data('empleado-id');
    let columnaNombre = tdEditado.data('columna-nombre');
    let valorInicial = 0;


    let inputSwal = "number";
    let inputAttributes = {};
    if (columnaNombre == "observaciones") {
      inputSwal = "textarea";
      valorInicial = tdEditado.text();
    } else {
      valorInicial = parseFloat(tdEditado.text().replace(/,/g, ''));
      inputAttributes = {
        step: 0.01
      };
    }

    Swal.fire({
      text: 'Editar valor',
      input: inputSwal,
      inputValue: valorInicial,
      inputAttributes: inputAttributes,
      showCancelButton: 'true',
      confirmButtonText: 'Actualizar',
      cancelButtonText: 'Cancelar',
      reverseButtons: 'true'
    }).then(function(result) {
      if (result.value) {
        let valorInput = ((inputSwal = "input" && result.value && result.value >= 0)) || (inputSwal = "textarea" && (result.value)) ? result.value : null;

        if (valorInicial != valorInput) {
          $.ajax({
            type: "POST",
            url: "../controller/Nominas/ActualizarValorEmpleado.php",
            data: {
              nominaId: "<?php echo $nominaId ?>",
              empleadoId: empleadoId,
              columnaNombre: columnaNombre,
              valor: valorInput,
            },
            success: function() {
              if (inputSwal == "input") {
                tdEditado.text(parseFloat(valorInput).toLocaleString('es-MX', {
                  minimumFractionDigits: 2
                })); //Actualizar valor en la vista
              } else {
                tdEditado.text(valorInput); //Actualizar valor en la vista
              }
              Toast.fire({
                icon: 'success',
                title: 'Valor actualizado'
              });
              calcularTotalesEmpleado(trEmpleado);
            },
            error: function(jqXHR, textStatus, errorThrown) {
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'No se pudo actualizar el valor. Intenta nuevamete'
              })
            }

          });
        }
      }
    })
  });

  function actualizarValorEmpleado(empleadoId, columnaNombre, valorInput) {
    //Método para actualizar solamente el valor del empleado, sin realizar cálculos
    $.ajax({
      type: "POST",
      url: "../controller/Nominas/ActualizarValorEmpleado.php",
      data: {
        nominaId: "<?php echo $nominaId ?>",
        empleadoId: empleadoId,
        columnaNombre: columnaNombre,
        valor: valorInput,
      },
      success: function() {
        if (!isNaN(valorInput)) {
          $("#e" + empleadoId + columnaNombre).text(valorInput.toLocaleString('es-MX', {
            minimumFractionDigits: 2
          }));
        } else {
          $("#e" + empleadoId + columnaNombre).text(valorInput);
        }
        Toast.fire({
          icon: 'success',
          title: 'Valor actualizado'
        });
      },
      error: function(jqXHR, textStatus, errorThrown) {
        Toast.fire({
          icon: 'error',
          title: 'No se pudo actualizar el valor. Intenta nuevamete'
        });
      }

    });
  }

  function calcularTotalesEmpleado(trEmpleado) {
    let empleadoId = trEmpleado.data('empleado-id');
    let tipoEmpleadoId = trEmpleado.data('tipo-empleado-id');

    if(tipoEmpleadoId != 2){
      let tipoGananciaId = trEmpleado.data('tipo-ganancia-id');
      let rutaId = trEmpleado.data('ruta-id');
      let extras = parseFloat(trEmpleado.find('td[data-columna-nombre="extras"]').text().replace(/,/g, '')) || 0;

      let diasTrabajados = parseFloat("<?php echo $diasTrabajados ?>");
      let sueldoBaseDiario = parseFloat(trEmpleado.find('td[data-columna-nombre="sueldo_base_dia"]').text().replace(/,/g, '')) || 0;
      let sueldoBaseTotal = 0;
      let sueldoBaseTotalOriginal = parseFloat(trEmpleado.find('td[data-columna-nombre="sueldo_base_total"]').text().replace(/,/g, '')) || 0;;

      if (tipoEmpleadoId == 4) { //Tipo empleado oficina y gerente no tiene salario por día trabajado, tienen salario fijo
        sueldoBaseTotal = sueldoBaseTotalOriginal;
      } else {
        sueldoBaseTotal = sueldoBaseDiario * diasTrabajados;
        if (sueldoBaseTotalOriginal != sueldoBaseTotal) {
          actualizarValorEmpleado(empleadoId, "sueldo_base_total", sueldoBaseTotal); //Actualizar valor en la tabla
        }
      }
      //Recalcular comisiones
      //Normales
      let cantidadNormal = parseFloat(trEmpleado.find('td[data-columna-nombre="cantidad_normal"]').text().replace(/,/g, '')) || 0;
      let colorCantidadNormal = getColorMetaEmpleado(tipoEmpleadoId, tipoGananciaId, 0, rutaId, cantidadNormal); //Sin descuento, es normal
      trEmpleado.find('td[data-columna-nombre="cantidad_normal"]').css("background-color", colorCantidadNormal);
      let comisionNormalOriginal = parseFloat(trEmpleado.find('td[data-columna-nombre="comisiones"]').text().replace(/,/g, '')) || 0;
      let comisionNormal = calcularComisionEmpleado(tipoEmpleadoId, tipoGananciaId, 0, rutaId, cantidadNormal);

      if (comisionNormalOriginal != comisionNormal) {
        actualizarValorEmpleado(empleadoId, "comisiones", comisionNormal); //Actualizar valor en la tabla
      }

      //Con descuento
      let cantidadDescuento = parseFloat(trEmpleado.find('td[data-columna-nombre="cantidad_descuento"]').text().replace(/,/g, '')) || 0;
      let colorCantidadDescuento = getColorMetaEmpleado(tipoEmpleadoId, tipoGananciaId, 1, rutaId, cantidadDescuento); //Con descuento
      trEmpleado.find('td[data-columna-nombre="cantidad_descuento"]').css("background-color", colorCantidadDescuento);
      let comisionDescuentoOriginal = parseFloat(trEmpleado.find('td[data-columna-nombre="comisiones_descuento"]').text().replace(/,/g, '')) || 0;
      let comisionDescuento = calcularComisionEmpleado(tipoEmpleadoId, tipoGananciaId, 1, rutaId, cantidadDescuento);

      if (comisionDescuentoOriginal != comisionDescuento) {
        actualizarValorEmpleado(empleadoId, "comisiones_descuento", comisionDescuento); //Actualizar valor en la tabla
      }
      //Fin recalcular comisiones

      //Calcular total
      let faltas = parseFloat(trEmpleado.find('td[data-columna-nombre="faltas"]').text().replace(/,/g, '')) || 0;
      let infonavit = parseFloat(trEmpleado.find('td[data-columna-nombre="infonavit"]').text().replace(/,/g, '')) || 0;
      let fondo = parseFloat(trEmpleado.find('td[data-columna-nombre="fondo"]').text().replace(/,/g, '')) || 0;

      let totalOriginal = parseFloat(trEmpleado.find('td[data-columna-nombre="total"]').text().replace(/,/g, '')) || 0;
      let total = extras + sueldoBaseTotal + comisionNormal + comisionDescuento - faltas - infonavit - fondo;
      if (totalOriginal != total) {
        actualizarValorEmpleado(empleadoId, "total", total); //Actualizar valor en la tabla
      }

      //Calcular efectivo (Total - Banco)
      let banco = parseFloat(trEmpleado.find('td[data-columna-nombre="banco"]').text().replace(/,/g, '')) || 0;
      let efectivoOriginal = parseFloat(trEmpleado.find('td[data-columna-nombre="efectivo"]').text().replace(/,/g, '')) || 0;
      let efectivo = total - banco;
      if (efectivoOriginal != efectivo) {
        actualizarValorEmpleado(empleadoId, "efectivo", efectivo); //Actualizar valor en la tabla
      }
    }

    calcularTotalGerente();
  }

  function calcularTotalGerente() {
    let tipoEmpleadoId = 2;
    let empleadoId = $("#gerente-detalle").data("empleado-id");
    let extras = parseFloat($('.gerente-extras').text().replace(/,/g, '')) || 0;
    let diasTrabajados = parseFloat("<?php echo $diasTrabajados ?>");
    let sueldoBaseTotal = parseFloat($('.gerente-sueldo_base_total').text().replace(/,/g, '')) || 0;

    totalComisiones = 0;

    //Calcular comisiones en base a todas las metas
    $.each(metasGerente, function(index, meta) {
      let metaId = meta.data.idmetazona;
      let comisionMeta = 0;
      let cantidadMeta = 0;

      $.each(meta.ambitos, function(index, ambito) {
        if (ambito.ambito_comision_id == 1) { //Total litros pipas contado
          $('.tr-te1').each(function(index) { //Empleado vendedor pipas (1)
            cantidadMeta += parseFloat($(this).find('td[data-columna-nombre="cantidad_normal"]').text().replace(/,/g, '')) || 0;
          });
        } else if (ambito.ambito_comision_id == 2) { //Total litros pipas con descuento
          $('.tr-te1').each(function(index) { //Empleado vendedor pipas (1)
            cantidadMeta += parseFloat($(this).find('td[data-columna-nombre="cantidad_descuento"]').text().replace(/,/g, '')) || 0;
          });
        } else if (ambito.ambito_comision_id == 3) { //Total litros estaciones
          $('.tr-te6').each(function(index) { //Empleado vendedor estación (6)
            cantidadMeta += parseFloat($(this).find('td[data-columna-nombre="cantidad_normal"]').text().replace(/,/g, '')) || 0;
          });
        } else if (ambito.ambito_comision_id == 4) { //Total cilindros
          $('.tr-te5').each(function(index) { //Empleado vendedor pipas (5)
            cantidadMeta += parseFloat($(this).find('td[data-columna-nombre="cantidad_normal"]').text().replace(/,/g, '')) || 0;
          });
        }
      });

      comisionMeta = calcularComisionGerente(metaId, cantidadMeta);
      $('#gerente' + metaId + 'comisiones').text(parseFloat(comisionMeta).toLocaleString('es-MX', {
        minimumFractionDigits: 2
      }));
      $('#gerente' + metaId + 'cantidadNormal').text(parseFloat(cantidadMeta).toLocaleString('es-MX', {
        minimumFractionDigits: 2
      }));
      let colorCantidadNormal = getColorMetaGerente(metaId, cantidadMeta);
      $('#gerente' + metaId + 'cantidadNormal').css("background-color", colorCantidadNormal);

      totalComisiones += parseFloat(comisionMeta);

    });

    //Recalcular comisiones

    let totalComisionesOriginal = parseFloat($('.gerente-comisiones').text().replace(/,/g, '')) || 0;

    if (totalComisionesOriginal != totalComisiones) {
      actualizarValorEmpleado(empleadoId, "comisiones", totalComisiones); //Actualizar valor en la tabla
    }
    //Fin recalcular comisiones

    //Calcular total
    let faltas = parseFloat($('.gerente-faltas').text().replace(/,/g, '')) || 0;
    let infonavit = parseFloat($('.gerente-infonavit').text().replace(/,/g, '')) || 0;

    let totalOriginal = parseFloat($('.gerente-total').text().replace(/,/g, '')) || 0;
    let total = extras + sueldoBaseTotal + totalComisiones - faltas - infonavit;
    if (totalOriginal != total) {
      actualizarValorEmpleado(empleadoId, "total", total); //Actualizar valor en la tabla
    }

    //Calcular efectivo (Total - Banco)
    let banco = parseFloat($('.gerente-banco').text().replace(/,/g, '')) || 0;
    let efectivoOriginal = parseFloat($('.gerente-efectivo').text().replace(/,/g, '')) || 0;
    let efectivo = total - banco;
    if (efectivoOriginal != efectivo) {
      actualizarValorEmpleado(empleadoId, "efectivo", efectivo); //Actualizar valor en la tabla
    }

    calcularTotalesNomina();
  }

  function calcularTotalesNomina() {
    let observaciones = null;
    let totalGral = 0;
    $('.empleado-total').each(function(index) {
      totalGral = totalGral + parseFloat($(this).text().replace(/,/g, ''));
    });

    let totalBanco = 0;
    $('.empleado-banco').each(function(index) {
      totalBanco = totalBanco + parseFloat($(this).text().replace(/,/g, ''));
    });

    let totalEfectivo = 0;
    $('.empleado-efectivo').each(function(index) {
      totalEfectivo = totalEfectivo + parseFloat($(this).text().replace(/,/g, ''));
    });

    $.ajax({
      type: "POST",
      url: "../controller/Nominas/ActualizarDatosNomina.php",
      data: {
        nominaId: "<?php echo $nominaId ?>",
        total: totalGral,
        banco: totalBanco,
        efectivo: totalEfectivo,
        observaciones: observaciones,
      },
      success: function() {
        $("#nominaTotal").text(parseFloat(totalGral).toLocaleString('es-MX', {
          minimumFractionDigits: 2
        }));
        $("#nominaBanco").text(parseFloat(totalBanco).toLocaleString('es-MX', {
          minimumFractionDigits: 2
        }));
        $("#nominaEfectivo").text(parseFloat(totalEfectivo).toLocaleString('es-MX', {
          minimumFractionDigits: 2
        }));

        Toast.fire({
          icon: 'success',
          title: 'Datos nómina actualizados'
        });
      },
      error: function(jqXHR, textStatus, errorThrown) {
        Toast.fire({
          icon: 'error',
          title: 'Datos nómina no actualizados'
        });
      }
    });
  }
</script>