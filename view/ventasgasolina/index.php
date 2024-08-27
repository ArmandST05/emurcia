<?php
$modelZona = new ModelZona();
$modelVentaGasolina = new ModelVentaGasolina();
$modelRuta = new ModelRuta();
$modelProducto = new ModelProducto();
if ($_SESSION["tipoUsuario"] == "u" && $_SESSION["tipoZona"] != 2) {
  echo "<script> 
          alert('Tu zona no vende GASOLINA... Redireccionando a Ventas Gas');
          window.location.href = 'index.php?action=ventasgasolina/index.php';
        </script>";
}

//Búsqueda de datos
$anio = date("Y");

$fechaMinima = date(("Y-m-d"),strtotime("-7 days"));
$fechaInicial = ((isset($_GET["fechaInicial"]) && ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc")) || ($_SESSION["tipoUsuario"] == "u" && isset($_GET["fechaInicial"]) >= $fechaMinima)) ? $_GET["fechaInicial"] : date("Y-m-d");
$fechaFinal = ((isset($_GET["fechaFinal"]) && ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc"))  || ($_SESSION["tipoUsuario"] == "u" && isset($_GET["fechaFinal"]) >= $fechaMinima)) ? $_GET["fechaFinal"] : date("Y-m-d");


$productoId = (isset($_GET["producto"])) ? $_GET["producto"] : 0;
$rutaId = (isset($_GET["ruta"])) ? $_GET["ruta"] : 0;

if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc") {
  $zonaId = (!empty($_GET["zona"])) ? $_GET["zona"] : "";

  if($zonaId){
    $zona = $modelZona->obtenerZonaId($zonaId);
    $zonaNombre = $zona["nombre"];
  }

  $zonas = $modelZona->obtenerZonasGasolina();
} else {
  $zonaId = $_SESSION['zonaId'];
  $zonaNombre = $_SESSION['zona'];
}

if ($rutaId != 0) {
  $rutasVenta[0] = $modelRuta->obtenerRutaId($rutaId);
} else {
  if ($productoId == 0) $rutasVenta = $modelVentaGasolina->rutasVentasEntreFechas($zonaId, $fechaInicial, $fechaFinal);
  else $rutasVenta = $modelVentaGasolina->rutasVentasProductoEntreFechas($zonaId, $productoId, $fechaInicial, $fechaFinal);
}

$productos = $modelProducto->index();
$fechaActual = date("Y-m-d");

$totalGralLitros = 0;
$totalGralPruebas = 0;
$totalGralCredito = 0;
$totalGralContado = 0;
$totalGralVenta = 0;

$anioData = date("Y");
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Ventas Gasolina</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Ventas Gasolina</h1>
  <?php if ($_SESSION["tipoUsuario"] == "u") : ?>
    <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" href="index.php?action=ventasgasolina/nuevo.php">Nueva</a>
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
          <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc") : ?>
            <div class="row">
              <div class="col-md-1">
                <div class="form-group">
                  <label>Zona:</label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <select class="form-control form-control-sm" name="zona" id="zona">
                    <option selected disabled>Seleciona opción</option>
                    <?php foreach ($zonas as $dataZona) : ?>
                      <option value="<?php echo $dataZona['idzona'] ?>" <?php echo ($zonaId == $dataZona['idzona']) ? "selected" : "" ?>>
                        <?php echo strtoupper($dataZona["nombre"]) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>
          <?php endif; ?>
          <div class="row">
            <div class="col-md-1">
              <div class="form-group">
                <label>Unidad:</label>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <select class="form-control form-control-sm" name="ruta" id="ruta">
                  <option value="0">Todas</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-1">
              <div class="form-group">
                <label>Producto:</label>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <select class="form-control form-control-sm" name="producto" id="producto">
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
            <div class="col-md-1">
              <div class="form-group">
                <label>Desde:</label>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <input class="form-control form-control-sm" type="date" id="fechaInicial" name="fechaInicial" value="<?php echo $fechaInicial ?>" <?php if($_SESSION["tipoUsuario"] == "u"):?> min="<?php echo $fechaMinima ?>" max="<?php echo $fechaActual?>" <?php endif; ?>>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-1">
              <div class="form-group">
                <label>Hasta:</label>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <input class="form-control form-control-sm" type="date" id="fechaFinal" name="fechaFinal" value="<?php echo $fechaFinal ?>" <?php if($_SESSION["tipoUsuario"] == "u"):?>  min="<?php echo $fechaMinima ?>" max="<?php echo $fechaActual?>" <?php endif; ?>>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2">
              <input type='hidden' name='action' id='action' value="ventasgasolina/index.php" />
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
        <table id="listaTabla" class="table table-bordered table-sm table-responsive" style="width:100%">
          <thead>
            <tr>
              <th>Fecha</th>
              <th>Hora</th>
              <th>Unidad</th>
              <th>Producto</th>
              <th>Precio</th>
              <th>Kilos</th>
              <th>Litros</th>
              <th>Pruebas</th>
              <th>Crédito</th>
              <th>Contado</th>
              <th>Total</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rutasVenta as $ruta) : ?>
              <tr>
                <td colspan="12"><b><?php echo $ruta["clave_ruta"] ?></b></td>
              </tr>
              <?php
              if ($productoId == 0) $fechas = $modelVentaGasolina->fechasVentasRuta($ruta["idruta"], $fechaInicial, $fechaFinal);
              else $fechas = $modelVentaGasolina->fechasVentasRutaProducto($ruta["idruta"], $productoId, $fechaInicial, $fechaFinal);
              $totalLitros = 0;
              $totalPruebas = 0;
              $totalCredito = 0;
              $totalContado = 0;
              $totalVenta = 0;
              ?>
              <?php
              foreach ($fechas as $fecha) :
                $fecha = $fecha["fecha"];

                if ($productoId == 0) $ventas = $modelVentaGasolina->listaZonaRutaFecha($zonaId, $ruta["idruta"], $fecha);
                else $ventas = $modelVentaGasolina->listaZonaRutaProductoFecha($zonaId, $ruta["idruta"], $productoId, $fecha);

                foreach ($ventas as $venta) :
                  //Venta de litros
                    $litros = $venta["cantidad"];
                    $totalLitros += $litros;

                    $kilos = ($litros * .524);
                    //$totalKilos += $kilos;


                  $totalCredito += $venta["total_venta_credito"];
                  $totalPruebas += $venta["pruebas"];
                  $totalContado += $venta["total_venta_contado"];
                  $totalVenta += $venta["total_venta"];
              ?>
                  <tr class="text-right">
                    <td><?php echo $venta["fecha"]; ?></td>
                    <td><?php echo $venta["hora"]; ?></td>
                    <td><?php echo $venta["ruta_nombre"]; ?></td>
                    <td><?php echo $venta["producto_nombre"]; ?></td>
                    <td><?php echo $venta["precio"]; ?></td>
                    <td><?php echo number_format($kilos, 2); ?></td>
                    <td><?php echo number_format($litros, 2); ?></td>
                    <td><?php echo number_format($venta['pruebas'], 2); ?></td>
                    <td>$<?php echo number_format($venta["total_venta_credito"],2); ?></td>
                    <td>$<?php echo number_format($venta["total_venta_contado"],2); ?></td>
                    <td>$<?php echo number_format($venta["total_venta"],2); ?></td>
                    <td>
                      <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "u") : ?>
                        <button class='btn btn-sm btn-primary' type='button' onclick="eliminar('<?php echo $venta['idventa']; ?>');"><i class='fas fa-trash fa-sm'></i></button>
                        <a class='btn btn-sm btn-light' href="index.php?action=ventasgasolina/editar.php&id=<?php echo $venta['idventa']; ?>"><i class='fas fa-pencil-alt'></i></a>
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
                <td></td>
                <td><b><?php echo number_format(($totalLitros * .524), 2) ?></b></td>
                <td><b><?php echo number_format($totalLitros, 2); ?></b></td>
                <td><b><?php echo number_format($totalPruebas, 2) ?></b></td>
                <td><b>$<?php echo number_format($totalCredito, 2) ?></b></td>
                <td><b>$<?php echo number_format($totalContado, 2) ?></b></td>
                <td><b>$<?php echo number_format($totalVenta, 2) ?></b></td>
                <td></td>
              </tr>
            <?php
              $totalGralLitros += $totalLitros;
              $totalGralPruebas += $totalPruebas;
              $totalGralCredito += $totalCredito;
              $totalGralContado += $totalContado;
              $totalGralVenta += $totalVenta;

            endforeach;
            ?>
            <tr>
              <th>Fecha</th>
              <th>Hora</th>
              <th>Unidad</th>
              <th>Producto</th>
              <th>Precio</th>
              <th>Kilos</th>
              <th>Litros</th>
              <th>Pruebas</th>
              <th>Crédito</th>
              <th>Contado</th>
              <th>Total</th>
              <th>Acciones</th>
            </tr>
            <tr class="bg-light text-right">
              <td><b>TOTAL</b></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td><b><?php echo number_format(($totalGralLitros * .524), 2) ?></b></td>
              <td><b><?php echo number_format($totalGralLitros, 2) ?></b></td>
              <td><b><?php echo number_format($totalGralPruebas, 2); ?></b></td>
              <td><b>$<?php echo number_format($totalGralCredito, 2) ?></b></td>
              <td><b>$<?php echo number_format($totalGralContado, 2) ?></b></td>
              <td><b>$<?php echo number_format($totalGralVenta, 2) ?></b></td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script type="text/JavaScript">
  $(document).ready(function(){
    obtenerRutas();
  });
  
  //Exportar a Excel
  $("#btnExport").click(function (e) {
    let zona = "<?php echo $zonaNombre ?>";
    let fechaInicial = "<?php echo $fechaInicial ?>";
    let fechaFinal = "<?php echo $fechaFinal ?>";

    $("#listaTabla").btechco_excelexport({
      containerid: "listaTabla"
      , datatype: $datatype.Table
      , filename: 'Reporte ventas-'+zona+'- De '+fechaInicial+' A '+fechaFinal
    });
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