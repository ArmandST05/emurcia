<?php
$modelZona = new ModelZona();
$modelClienteDescuento = new ModelClienteDescuento();
$modelRuta = new ModelRuta();
$modelProducto = new ModelProducto();

//Búsqueda de datos
$fechaMinima = date(("Y-m-d"), strtotime("-7 days"));
$fechaInicial = ((isset($_GET["fechaInicial"]) && ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc")) || ($_SESSION["tipoUsuario"] == "u" && isset($_GET["fechaInicial"]) >= $fechaMinima)) ? $_GET["fechaInicial"] : date("Y-m-d");
$fechaFinal = ((isset($_GET["fechaFinal"]) && ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc"))  || ($_SESSION["tipoUsuario"] == "u" && isset($_GET["fechaFinal"]) >= $fechaMinima)) ? $_GET["fechaFinal"] : date("Y-m-d");

$productoId = (isset($_GET["producto"])) ? $_GET["producto"] : 0;
$rutaId = (isset($_GET["ruta"])) ? $_GET["ruta"] : 0;
$clienteDescuentoId = (isset($_GET["cliente"])) ? $_GET["cliente"] : 0;

if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc") {
  $zonaId = (!empty($_GET["zona"])) ? $_GET["zona"] : "";
  if ($zonaId && $zonaId != "all") {
    $zona = $modelZona->obtenerZonaId($zonaId);
    $zonaNombre = $zona["nombre"];
  }

  $zonas = $modelZona->obtenerZonasGas();
} else {
  $zonaId = $_SESSION['zonaId'];
  $zonaNombre = $_SESSION['zona'];
}

$productos = $modelProducto->index();
$fechaActual = date("Y-m-d");
$totalDescuento = 0;
$descuentos = $modelClienteDescuento->listaZonaFechaZonaRutaProducto($fechaInicial, $fechaFinal, $zonaId, $rutaId, $productoId, $clienteDescuentoId);
$clientesDescuento = $modelClienteDescuento->listaZonaEstatus($zonaId, 1);
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Reporte Descuentos</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Reporte Descuentos</h1>
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
            <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc") : ?>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Zona:</label>
                  <select class="form-control form-control-sm" name="zona" id="zona" required>
                    <!--<option value="all" <?php echo ($zonaId == "all") ? "selected" : "" ?>>---TODAS---</option>-->
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
                <label>Cliente:</label>
                <select class="form-control form-control-sm" name="cliente" id="cliente">
                  <option value="0" <?php echo ($clienteDescuentoId == 0) ? "selected":""?>>Todos</option>
                  <?php foreach ($clientesDescuento as $clienteDescuento) : ?>
                    <option value="<?php echo $clienteDescuento['idclientedescuento'] ?>" <?php echo ($clienteDescuentoId == $clienteDescuento['idclientedescuento']) ? "selected":""?>>
                      <?php echo $clienteDescuento['nombre'] ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
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
            <div class="col-md-4">
              <div class="form-group">
                <label>Desde:</label>
                <input class="form-control form-control-sm" type="date" id="fechaInicial" name="fechaInicial" value="<?php echo $fechaInicial ?>" <?php echo (($_SESSION["tipoUsuario"] == "u") ? "min='" . $fechaMinima . "'" : "max='" . $fechaActual . "'") ?>>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Hasta:</label>
                <input class="form-control form-control-sm" type="date" id="fechaFinal" name="fechaFinal" value="<?php echo $fechaFinal ?>" <?php echo (($_SESSION["tipoUsuario"] == "u") ? "min='" . $fechaMinima . "'" : "max='" . $fechaActual . "'") ?>>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2 offset-md-11">
              <input type='hidden' name='action' id='action' value="clientesdescuento/reporte.php" />
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
        <h6 class="m-0 font-weight-bold text-primary">Descuentos</h6>
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
                  <th>Ruta</th>
                  <th>Producto</th>
                  <th>Cliente</th>
                  <th>Descuento otorgado</th>
                  <th>Cantidad Vendida (Lts/Kg)</th>
                  <th>Total</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($descuentos as $descuento) :
                  $totalDescuento += $descuento["total"];
                ?>
                  <tr class="text-right">
                    <td><?php echo $descuento["fecha"]; ?></td>
                    <td><?php echo $descuento["ruta_nombre"]; ?></td>
                    <td><?php echo $descuento["producto_nombre"]; ?></td>
                    <td><?php echo $descuento["cliente_nombre"]; ?></td>
                    <td><?php echo $descuento["descuento_cantidad"]; ?></td>
                    <td><?php echo $descuento["cantidad"]; ?></td>
                    <td>$<?php echo number_format($descuento["total"], 2); ?></td>
                    <td></td>
                  </tr>
                <?php endforeach; ?>
                <tr>
                  <th>Fecha</th>
                  <th>Ruta</th>
                  <th>Producto</th>
                  <th>Cliente</th>
                  <th>Descuento otorgado</th>
                  <th>Cantidad Vendida (Lts/Kg)</th>
                  <th>Total</th>
                  <th>Acciones</th>
                </tr>
                <tr class="bg-light text-right">
                  <td><b>TOTAL</b></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td><b>$<?php echo number_format($totalDescuento, 2) ?></b></td>
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
    
    $('#cliente').select2({});
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
          , filename: 'Reporte descuentos-'+zona+'- De '+fechaInicial+' A '+fechaFinal
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
          $("#ruta").append('<option value='+ ruta.id + '>'+ ruta.clave_ruta +' '+estatus_ruta+'</option>');
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