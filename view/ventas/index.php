<?php
$modelZona = new ModelZona();
$modelVenta = new ModelVenta();
$modelRuta = new ModelRuta();
$modelProducto = new ModelProducto();
//Búsqueda de datos
$fechaMinima = date(("Y-m-d"), strtotime("-7 days"));
$fechaInicial = ((isset($_GET["fechaInicial"]) && ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc")) || (($_SESSION["tipoUsuario"] == "u" || $_SESSION['tipoUsuario'] == "mv") && isset($_GET["fechaInicial"]) >= $fechaMinima)) ? $_GET["fechaInicial"] : date("Y-m-d");
$fechaFinal = ((isset($_GET["fechaFinal"]) && ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc"))  || (($_SESSION["tipoUsuario"] == "u" || $_SESSION['tipoUsuario'] == "mv") && isset($_GET["fechaFinal"]) >= $fechaMinima)) ? $_GET["fechaFinal"] : date("Y-m-d");

$productoId = (isset($_GET["producto"])) ? $_GET["producto"] : 0;
$rutaId = (isset($_GET["ruta"])) ? $_GET["ruta"] : 0;

if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc") {
  $zonaId = (!empty($_GET["zona"])) ? $_GET["zona"] : "";
  if ($zonaId && $zonaId != "all") {
    $zona = $modelZona->obtenerZonaId($zonaId);
    $zonaNombre = $zona["nombre"];
  }

  $zonas = $modelZona->obtenerZonasGas();
} elseif ($_SESSION["tipoUsuario"] == "mv") { //Es un usuario multizona de captura de ventas
  $zonas = $modelZona->obtenerZonasPorUsuario($_SESSION["id"]);

  $zonaId = (!empty($_GET["zona"])) ? $_GET["zona"] : $zonas[0]["idzona"];
  if ($zonaId && $zonaId != "all") {
    $zona = $modelZona->obtenerZonaId($zonaId);
    $zonaNombre = $zona["nombre"];
  }
} else {
  $zonaId = $_SESSION['zonaId'];
  $zonaNombre = $_SESSION['zona'];
}

$productos = $modelProducto->index();
$fechaActual = date("Y-m-d");
//Variables para totales
$totalGralLitros = 0;
$totalGralCil = 0;
$totalGralCredito = 0;
$totalGralDescCredito = 0;
$totalGralContado = 0;
$totalGralDescContado = 0;
$totalGralVenta = 0;
$totalGralPrecioLleno = 0;
$totalGralLtsCredito = 0;
$totalGralLtsContado = 0;
$totalGralLtsDescContado = 0;

if ($_SESSION["tipoUsuario"] == "su" && $zonaId == "all") { //Obtener total de todas las zonas
  $ventas = $modelVenta->listaVentasProductoFecha($productoId, $fechaInicial, $fechaFinal);
  foreach ($ventas as $venta) {
    //Venta de litros en pipas (1)/estación carburación (5)/plantas lts (4)
    if ($venta["producto_id"] == 4) {
      $litros = ($venta["total_rubros_venta"] * $venta["producto_capacidad"]);
      $totalGralLitros += $litros;
    } else {
      //Venta de kg en Cilindreras (2)/Planta Cilindros (3)
      $kilos = ($venta["total_rubros_venta"] * $venta["producto_capacidad"]);
      $totalGralLitros += ($kilos / .524);
    }

    $totalGralCil += ($venta["tipo_ruta_id"] == 2 || $venta["tipo_ruta_id"] == 3) ? $venta["total_rubros_venta"] : 0;

    //Dividir el total de la venta que fue a crédito entre el precio al que se vendió para obtener los litros a crédito. 
    //Sacarlo el cálculo en base a lo total $ vendido.
    $totalGralLtsCredito += ($venta["total_venta_credito"] + $venta["descuento_total_venta_credito"]) / $venta["precio"];
    $totalGralLtsContado += ($venta["total_venta_contado"] + $venta["descuento_total_venta_contado"]) / $venta["precio"];

    $totalGralCredito += $venta["total_venta_credito"];
    $totalGralDescCredito += $venta["descuento_total_venta_credito"];
    $totalGralContado += $venta["total_venta_contado"];
    $totalGralDescContado += $venta["descuento_total_venta_contado"];
    $totalGralVenta += ($venta["total_venta"] - $venta["descuento_total_venta_credito"] - $venta["descuento_total_venta_contado"]);
    $totalGralPrecioLleno += $venta["total_venta"];
  }
} else { //Mostrar filtro por zona
  if ($rutaId != 0) {
    $rutasVenta[0] = $modelRuta->obtenerRutaId($rutaId);
  } else {
    if ($productoId == 0) $rutasVenta = $modelVenta->rutasVentasEntreFechas($zonaId, $fechaInicial, $fechaFinal);
    else $rutasVenta = $modelVenta->rutasVentasProductoEntreFechas($zonaId, $productoId, $fechaInicial, $fechaFinal);
  }
}
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Ventas Gas</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Ventas Gas</h1>
  <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "u" || $_SESSION['tipoUsuario'] == "mv") : ?>
    <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" href="index.php?action=ventas/nuevo.php<?php echo ($_SESSION["tipoUsuario"] == 'mv') ? '&zonaId=' . $zonaId : '' ?>">Nueva</a>
  <?php endif; ?>
</div>
<!-- Content Row -->
<div class="row">
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Header - Dropdown -->
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Buscar</h6>
      </div>
      <!-- Card Body -->
      <div class="card-body">
        <form action='index.php' method='GET'>
          <div class="row">
            <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "mv") : ?>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Zona:</label>
                  <select class="form-control form-control-sm" name="zona" id="zona" required>
                    <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc") : ?>
                      <option value="all" <?php echo ($zonaId == "all") ? "selected" : "" ?>>---TODAS---</option>
                    <?php endif; ?>
                    <?php foreach ($zonas as $dataZona) : ?>
                      <option value="<?php echo $dataZona['idzona'] ?>" <?php echo ($zonaId == $dataZona['idzona']) ? "selected" : "" ?>>
                        <?php echo strtoupper($dataZona["nombre"]) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            <?php endif; ?>
            <div class="col-md-4">
              <div class="form-group">
                <label>Ruta:</label>
                <select class="form-control form-control-sm" name="ruta" id="ruta" required>
                  <option value="0">Todas</option>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Producto:</label>
                <select class="form-control form-control-sm" name="producto" id="producto" required>
                  <option value="0">Todos</option>
                  <?php foreach ($productos as $producto) : ?>
                    <option value="<?php echo $producto['idproducto'] ?>" <?php echo ($producto['idproducto'] == $productoId) ? 'selected' : '' ?>>
                      <?php echo $producto['nombre'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Desde:</label>
                <input class="form-control form-control-sm" type="date" id="fechaInicial" name="fechaInicial" value="<?php echo $fechaInicial ?>" <?php echo (($_SESSION["tipoUsuario"] == "u" || $_SESSION['tipoUsuario'] == "mv") ? "min='" . $fechaMinima . "'" : "max='" . $fechaActual . "'") ?>>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Hasta:</label>
                <input class="form-control form-control-sm" type="date" id="fechaFinal" name="fechaFinal" value="<?php echo $fechaFinal ?>" <?php echo (($_SESSION["tipoUsuario"] == "u" || $_SESSION['tipoUsuario'] == "mv") ? "min='" . $fechaMinima . "'" : "max='" . $fechaActual . "'") ?>>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2 pull-right">
              <input type='hidden' name='action' id='action' value="ventas/index.php" />
              <input class="btn btn-primary btn-sm" type='submit' id='busqueda' value='Buscar'>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Content Row -->
<div class="row">
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Header -->
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Lista Ventas</h6>
      </div>
      <!-- Card Body -->
      <div class="card-body">
        <div class="row">
          <div class="col-md-2 offset-md-10">
            <button class="btn btn-sm btn-warning" id="btnExport"><i class="far fa-file-excel"></i> Exportar Excel</button>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <table id="listaTabla" class="table table-bordered table-sm table-responsive" style="width:100%;font-size: .80rem;">
              <thead>
                <tr>
                  <th>Fecha</th>
                  <?php if ($zonaId != "all") : ?>
                    <th>Producto</th>
                    <th>LI</th>
                    <th>LF</th>
                  <?php endif; ?>
                  <th>Kg</th>
                  <th>Lts</th>
                  <th>Cilindros</th>
                  <th>Crédito</th>
                  <th>Lts Crédito</th>
                  <th>Desc. Crédito</th>
                  <th>Contado</th>
                  <th>Lts Contado</th>
                  <th>Lts Desc. Contado</th>
                  <th>Desc. Contado</th>
                  <th>Total Venta</th>
                  <th>Precio Lleno</th>
                  <th>Comp.</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($zonaId != "all") : ?>
                  <?php foreach ($rutasVenta as $claveRuta => $ruta) : ?>
                    <tr data-tt-id="r<?php echo $claveRuta ?>">
                      <td colspan="15"><b><?php echo $ruta["clave_ruta"] ?></b></td>
                    </tr>
                    <?php
                    if ($productoId == 0) $fechas = $modelVenta->fechasVentasRuta($ruta["idruta"], $fechaInicial, $fechaFinal);
                    else $fechas = $modelVenta->fechasVentasRutaProducto($ruta["idruta"], $productoId, $fechaInicial, $fechaFinal);
                    $totalLitros = 0;
                    $totalKilos = 0;
                    $totalCil = 0;
                    $totalCredito = 0;
                    $totalDescCredito = 0;
                    $totalContado = 0;
                    $totalLtsDescContado = 0;
                    $totalDescContado = 0;
                    $totalVenta = 0;
                    $totalPrecioLleno = 0;
                    $totalLtsCredito = 0;
                    $totalLtsContado = 0;
                    ?>
                    <?php
                    foreach ($fechas as $claveFecha => $fecha) :
                      $fecha = $fecha["fecha"];

                      if ($productoId == 0) $ventas = $modelVenta->listaZonaRutaFecha($zonaId, $ruta["idruta"], $fecha);
                      else $ventas = $modelVenta->listaZonaRutaProductoFecha($zonaId, $ruta["idruta"], $productoId, $fecha);

                      foreach ($ventas as $venta) :
                        //Venta de litros en pipas (1)/estación carburación (5)/plantas lts (4)
                        if ($venta["producto_id"] == 4) {
                          $litros = ($venta["total_rubros_venta"] * $venta["producto_capacidad"]);
                          $totalLitros += $litros;

                          $kilos = ($litros * .524);
                          $totalKilos += $kilos;
                        } else {
                          //Venta de kg en Cilindreras (2)/Planta Cilindros (3)
                          $kilos = ($venta["total_rubros_venta"] * $venta["producto_capacidad"]);
                          $totalKilos += $kilos;

                          $litros = ($kilos / .524);
                          $totalLitros += $litros;
                        }
                        $cilindros = ($venta["tipo_ruta_id"] == 2 || $venta["tipo_ruta_id"] == 3) ? $venta["total_rubros_venta"] : 0;
                        $totalCil += $cilindros;

                        //Dividir el total de la venta que fue a crédito entre el precio al que se vendió para obtener los litros a crédito. 
                        //Sacarlo el cálculo en base a lo total $ vendido.
                        $ltsCredito = ($venta["total_venta_credito"] + $venta["descuento_total_venta_credito"]) / $venta["precio"];
                        $ltsContado = ($venta["total_venta_contado"] + $venta["descuento_total_venta_contado"]) / $venta["precio"];

                        $totalCredito += $venta["total_venta_credito"];
                        $totalDescCredito += $venta["descuento_total_venta_credito"];
                        $totalContado += $venta["total_venta_contado"];       
                        $totalLtsDescContado += $venta["cantidad_venta_contado"];
                        $totalDescContado += $venta["descuento_total_venta_contado"];
                        $totalVenta += ($venta["total_venta"] - $venta["descuento_total_venta_credito"] - $venta["descuento_total_venta_contado"]);
                        $totalPrecioLleno += $venta["total_venta"];

                        $totalLtsCredito += $ltsCredito;
                        $totalLtsContado += $ltsContado;

                        //Total Venta en Reporte= (Crédito + Contado) o (Precio lleno - descuento crédito - descuento contado)
                        //Precio Lleno = Litros por el precio público sin descuento

                        //Venta - Empleados
                        $empleados = $modelVenta->obtenerVentaEmpleados($venta["idventa"]);
                        $empleadosString = "";
                        foreach($empleados as $empleado){
                          $empleadosString .= $empleado["nombre"]."/ ";
                        }
                        
                    ?>
                        <tr data-tt-id="f<?php echo $claveFecha ?>" data-tt-parent-id="r<?php echo $claveRuta ?>" class="text-right">
                          <td><?php echo $venta["fecha"]."<br>".$empleadosString; ?></td>
                          <td><?php echo $venta["producto_nombre"]; ?></td>
                          <td><?php echo ($venta["tipo_ruta_id"] == 1 || $venta["tipo_ruta_id"] == 5) ? $venta["lectura_inicial"] : 0; ?></td>
                          <td><?php echo ($venta["tipo_ruta_id"] == 1 || $venta["tipo_ruta_id"] == 5) ? $venta["lectura_final"] : 0; ?></td>
                          <td><?php echo number_format($kilos, 2); ?></td>
                          <td><?php echo number_format($litros, 2); ?></td>
                          <td><?php echo number_format($cilindros, 2) ?></td>
                          <td>$<?php echo number_format($venta["total_venta_credito"], 2); ?></td>
                          <td><?php echo number_format($ltsCredito, 2); ?></td>
                          <td>$<?php echo number_format($venta["descuento_total_venta_credito"], 2); ?></td>
                          <td>$<?php echo number_format($venta["total_venta_contado"], 2); ?></td>
                          <td><?php echo number_format($ltsContado, 2); ?></td>
                          <td><?php echo number_format($venta["cantidad_venta_contado"], 2); ?></td>
                          <td>$<?php echo number_format($venta["descuento_total_venta_contado"], 2); ?></td>
                          <td>$<?php echo number_format(($venta["total_venta"] - $venta["descuento_total_venta_credito"] - $venta["descuento_total_venta_contado"]), 2); ?></td>
                          <td>$<?php echo number_format($venta["total_venta"], 2); ?></td>
                          <td>
                              <?php 
                                $comprobante = $modelVenta->obtenerComprobanteVenta($venta["idventa"]);
                                
                              ?>
                               <?php if ($comprobante && !empty($comprobante)): ?>
                                      <a href="<?php echo 'https://cgtest.v2technoconsulting.com/view/ventas/comprobantes/' . basename($comprobante['comprobante_venta']); ?>" download style="margin-right: 25px;">
                                          <i class="fas fa-download" aria-hidden="true" ></i> <!-- Icono de descarga -->
                                      </a>
                                  <?php else: ?>
                                      No disponible
                                  <?php endif; ?>
                          </td>
                          <td>
                            <?php if (($fecha == $fechaActual && ($_SESSION["tipoUsuario"] == "u"  || $_SESSION['tipoUsuario'] == "mv")) || ($_SESSION["tipoUsuario"] == "su" && $venta["total_ventas_posteriores"] == 0)) : ?>
                              <button class='btn btn-sm btn-primary' type='button' onclick="eliminar('<?php echo $venta['idventa']; ?>');"><i class='fas fa-trash fa-sm'></i></button>
                              <a class='btn btn-sm btn-light' href="index.php?action=ventas/editar.php&id=<?php echo $venta['idventa']; ?>"><i class='fas fa-pencil-alt'></i></a>
                            <?php elseif($_SESSION["tipoUsuario"] == "su"):?>
                              <a class='btn btn-sm btn-light' href="index.php?action=ventas/editar.php&id=<?php echo $venta['idventa']; ?>"><i class='fas fa-pencil-alt'></i></a>
                            <?php endif; ?>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                      <!--Fechas -->
                    <?php endforeach; ?>
                    <tr class="bg-light text-right">
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td><b><?php echo number_format(($totalLitros * .524), 2) ?></b></td>
                      <td><b><?php echo number_format($totalLitros, 2); ?></b></td>
                      <td><b><?php echo number_format($totalCil, 2) ?></b></td>
                      <td><b>$<?php echo number_format($totalCredito, 2) ?></b></td>
                      <td><b><?php echo number_format($totalLtsCredito, 2) ?></b></td>
                      <td><b>$<?php echo number_format($totalDescCredito, 2) ?></b></td>
                      <td><b>$<?php echo number_format($totalContado, 2) ?></b></td>
                      <td><b><?php echo number_format($totalLtsContado, 2) ?></b></td>
                      <td><b><?php echo number_format($totalLtsDescContado, 2) ?></b></td>
                      <td><b>$<?php echo number_format($totalDescContado, 2) ?></b></td>
                      <td><b>$<?php echo number_format($totalVenta, 2) ?></b></td>
                      <td><b>$<?php echo number_format($totalPrecioLleno, 2) ?></b></td>
                      <td></td>
                    </tr>
                  <?php
                    $totalGralLitros += $totalLitros;
                    $totalGralCil += $totalCil;
                    $totalGralCredito += $totalCredito;
                    $totalGralDescCredito += $totalDescCredito;
                    $totalGralContado += $totalContado;
                    $totalGralLtsDescContado += $totalLtsDescContado;
                    $totalGralDescContado += $totalDescContado;
                    $totalGralVenta += $totalVenta;
                    $totalGralPrecioLleno += $totalPrecioLleno;

                    $totalGralLtsCredito += $totalLtsCredito;
                    $totalGralLtsContado += $totalLtsContado;

                  endforeach;
                  ?>
                  <tr>
                    <th>Fecha</th>
                    <th>Producto</th>
                    <th>LI</th>
                    <th>LF</th>
                    <th>Kg</th>
                    <th>Lts</th>
                    <th>Cilindros</th>
                    <th>Crédito</th>
                    <th>Lts. Crédito</th>
                    <th>Desc. Crédito</th>
                    <th>Contado</th>
                    <th>Lts Contado</th>
                    <th>Lts Desc. Contado</th>
                    <th>Desc. Contado</th>
                    <th>Total Venta</th>
                    <th>Precio Lleno</th>
                    <th>Acciones</th>
                  </tr>
                <?php endif; ?>
                <tr class="bg-light text-right">
                  <td><b>TOTAL</b></td>
                  <?php if ($zonaId != "all") : ?>
                    <td></td>
                    <td></td>
                    <td></td>
                  <?php endif; ?>
                  <td><b><?php echo number_format(($totalGralLitros * .524), 2) ?></b></td>
                  <td><b><?php echo number_format($totalGralLitros, 2) ?></b></td>
                  <td><b><?php echo number_format($totalGralCil, 2); ?></b></td>
                  <td><b>$<?php echo number_format($totalGralCredito, 2) ?></b></td>
                  <td><b><?php echo number_format($totalGralLtsCredito, 2) ?></b></td>
                  <td><b>$<?php echo number_format($totalGralDescCredito, 2) ?></b></td>
                  <td><b>$<?php echo number_format($totalGralContado, 2) ?></b></td>
                  <td><b><?php echo number_format($totalGralLtsContado, 2) ?></b></td>
                  <td><b><?php echo number_format($totalGralLtsDescContado, 2) ?></b></td>
                  <td><b>$<?php echo number_format($totalGralDescContado, 2) ?></b></td>
                  <td><b>$<?php echo number_format($totalGralVenta, 2) ?></b></td>
                  <td><b>$<?php echo number_format($totalGralPrecioLleno, 2) ?></b></td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/JavaScript">
  $(document).ready(function(){
    obtenerRutas();
  });

  $("#listaTabla").treetable({ 
    expandable: true 
  });
  $('#listaTabla').treetable('expandAll');
  
  //Exportar a Excel
  $("#btnExport").click(function (e) {
    $('#listaTabla').treetable('expandAll');
    let zona = "<?php echo $zonaNombre ?>";
    let fechaInicial = "<?php echo $fechaInicial ?>";
    let fechaFinal = "<?php echo $fechaFinal ?>";
    setTimeout(function(){
        $("#listaTabla").btechco_excelexport({
          containerid: "listaTabla"
          , datatype: $datatype.Table
          , filename: 'Reporte ventas-'+zona+'- De '+fechaInicial+' A '+fechaFinal
        });
      }, 12000);
  });

  $("#zona").change(function() {
    obtenerRutas();
  });

  function obtenerRutas(){
    let tipoUsuario = "<?php echo $_SESSION["tipoUsuario"] ?>";

    let zonaId = 0;
    if(tipoUsuario == "su" || tipoUsuario == "uc") zonaId = $("#zona").val();
    else zonaId = "<?php echo $_SESSION["zonaId"] ?>";

    $("#ruta").empty().append('<option value="0" selected>Todas</option>');

    $.ajax({
      data: { zonaId : zonaId },
      type: "GET",
      url: '../controller/Rutas/ObtenerPorZonaVenta.php', 
      dataType: "json",
      success: function(data){
        $.each(data,function(key, ruta) {
          let estatus_ruta = "";
          if(ruta.estatus == 0) estatus_ruta = "(INACTIVO)"; 
          $("#ruta").append('<option value='+ ruta.idruta + '>'+ ruta.clave_ruta +' '+estatus_ruta+'</option>');
        });
        let rutaId = "<?php echo $rutaId ?>";  
        $("#ruta").val(rutaId).change();    
      },
      error: function(data) {
        alertify.error('Ha ocurrido un error al cargar las rutas de la zona.');
      }
    });
  }

  function eliminar(id) {
    alertify.confirm("¿Realmente desea eliminar la venta?",
        function() {
          $.ajax({
            type: "POST",
            url: "../controller/Ventas/Eliminar.php",
            data: {
              id: id
            },
            success: function(data) {
              location.reload();
              alertify.success("Venta eliminada");
            },
            error: function(data) {
              alertify.success("No se pudo eliminar la venta");
            }
          });
        },
        function() {})
      .set({
        title: "Eliminar Venta"
      })
      .set({
        labels: {
          ok: 'Aceptar',
          cancel: 'Cancelar'
        }
      });
  }
</script>