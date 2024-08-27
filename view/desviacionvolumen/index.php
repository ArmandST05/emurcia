<?php
$modelZona = new ModelZona();
$modelRuta = new ModelRuta();
$modelProducto = new ModelProducto();
$modelDesviacionVolumen = new ModelDesviacionVolumen();
$anio = date("Y");

$fechaInicial = (isset($_GET["fechaInicial"])) ? $_GET["fechaInicial"] : date("Y-m-d");
$fechaFinal = (isset($_GET["fechaFinal"])) ? $_GET["fechaFinal"] : date("Y-m-d");

$productoId = (isset($_GET["producto"])) ? $_GET["producto"] : 0;
$rutaId = (isset($_GET["ruta"])) ? $_GET["ruta"] : 0;
$zonaNombre = "";

if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "inv") {
  $zonaId = (!empty($_GET["zona"])) ? $_GET["zona"] : "";

  $zonas = $modelZona->obtenerZonasGas();
  $zonas = array_merge($zonas, $modelZona->obtenerZonasGasolina());
} else {
  $zonaId = $_SESSION['zonaId'];
  $zonaNombre = $_SESSION['zona'];
}

if ($zonaId) {
  $zona = $modelZona->obtenerZonaId($zonaId);
  $zonaNombre = $zona["nombre"];
  $zonaTipo = $zona["tipo_zona_id"];
  if ($rutaId) {
    $ruta = $modelRuta->obtenerRutaId($rutaId);
  }
}

$detallesDesviacion = $modelDesviacionVolumen->listaPorZonaRutaFechas($zonaId, $rutaId, $productoId, $fechaInicial, $fechaFinal);

$productos = $modelProducto->index();
$fechaActual = date("Y-m-d");

$promedioDesviacion = 0;
$promedioPorcentajeMerma = 0;
$totalRegistros = 0;
$sumDesviacion = 0;
$sumPorcentajeMerma = 0;
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Sistema Gestión de Medición</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Sistema Gestión de Medición</h1>
  <?php if ($_SESSION["tipoUsuario"] == "u") : ?>
    <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" href="index.php?action=desviacionvolumen/nuevo.php">Nueva</a>
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
            <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "inv") : ?>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Zona:</label>
                  <select class="form-control form-control-sm" name="zona" id="zona">
                    <option selected disabled>Seleciona opción</option>
                    <?php foreach ($zonas as $dataZona) : ?>
                      <option data-tipo-zona="<?php echo $dataZona['tipo_zona_id'] ?>" value="<?php echo $dataZona['idzona'] ?>" <?php echo ($zonaId == $dataZona['idzona']) ? "selected" : "" ?>>
                        <?php echo strtoupper($dataZona["nombre"]) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            <?php endif; ?>
            <div class="col-md-4">
              <div class="form-group">
                <label>Almacén:</label>
                <select class="form-control form-control-sm" name="ruta" id="ruta">
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Producto:</label>
                <select class="form-control form-control-sm" name="producto" id="producto">
                  <option value="0">Todos</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Desde:</label>
                <input class="form-control form-control-sm" type="date" id="fechaInicial" name="fechaInicial" value="<?php echo $fechaInicial ?>">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Hasta:</label>
                <input class="form-control form-control-sm" type="date" id="fechaFinal" name="fechaFinal" value="<?php echo $fechaFinal ?>">
              </div>
            </div>
            <div class="col-md-2">
              <br>
              <input type='hidden' name='action' id='action' value="desviacionvolumen/index.php" />
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
        <h6 class="m-0 font-weight-bold text-primary">LISTA DETALLES <?php echo ($rutaId) ? $ruta["clave_ruta"] . " (" . $ruta["capacidad"] . ")" : "" ?></h6>
      </div>
      <!-- Card Body -->
      <div class="card-body">
        <?php if ($zonaId && $zona["permiso_descripcion"]) : ?>
          <div class="row">
            <?php echo $zona["permiso_descripcion"] ?>
          </div>
        <?php endif; ?>
        <br>
        <div class="row">
          <div class="col-md-2 offset-md-10">
            <button class="btn btn-sm btn-warning" id="btnExport"><i class="far fa-file-excel"></i> Exportar Excel</button>
          </div>
        </div>
        <br>
        <table id="listaTabla" class="table table-bordered table-sm table-responsive" style="width:100%">
          <thead>
            <tr>
              <th>Fecha</th>
              <th>Factura/Remisión</th>
              <th>Volumen factura a 20°C</th>
              <th>Proveedor</th>
              <th>Transporte</th>
              <th>Tanque descarga</th>
              <th>Volumen descarga bruto</th>
              <th>Venta durante descarga</th>
              <th>Volumen desviación Factura vs Veeder root</th>
              <th>Desviación < 0.5% Factura vs Veeder Root</th>
              <th>Inventario Inicial</th>
              <th>Compras del día</th>
              <th>Incremento de combustible existente</th>
              <th>Inventario Final</th>
              <th>Litros vendidos veeder root</th>
              <th>Litros sistema vendidos (I-Gas)</th>
              <th>Diferencia entre veeder root vs I Gas</th>
              <th>Porcentaje de merma < 5%</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($detallesDesviacion as $detalle) :
              $volumenDesviacion = ($detalle["volumen_factura"] != 0) ? ($detalle["volumen_factura"] - $detalle["volumen_descarga_bruto"]) : 0;
              $desviacion = ($detalle["volumen_factura"]) ? (($volumenDesviacion / $detalle["volumen_factura"]) * 100) : 0;
              $incrementoCombustible = $detalle["inventario_inicial"] + floatval($detalle["compras_dia"]);
              $totalVendidoRoot = ($incrementoCombustible != 0) ? ($incrementoCombustible - $detalle["inventario_final"]) : 0;
              $diferenciaVendido = ($totalVendidoRoot != 0) ? ($totalVendidoRoot - floatval($detalle["total_vendido_sistema"])) : 0;
              $porcentajeMerma = ($diferenciaVendido != 0) ? (($diferenciaVendido / $totalVendidoRoot) * 100) : 0;

              $totalRegistros++;
              $sumDesviacion += number_format($desviacion, 2);
              $sumPorcentajeMerma += number_format($porcentajeMerma, 2);
            ?>
              <tr class="text-right">
                <td><?php echo $detalle["fecha"]; ?></td>
                <td><?php echo $detalle["factura_remision"]; ?></td>
                <td><?php echo $detalle["volumen_factura"]; ?></td>
                <td><?php echo $detalle["proveedor_nombre"]; ?></td>
                <td><?php echo $detalle["transporte"]; ?></td>
                <td><?php echo $detalle["tanque_descarga"]; ?></td>
                <td><?php echo number_format($detalle["volumen_descarga_bruto"], 2); ?></td>
                <td><?php echo number_format($detalle["venta_descarga"], 2); ?></td>
                <td><?php echo number_format($volumenDesviacion, 2); ?></td>
                <td style="background-color:<?php echo ($desviacion > 0.5) ? '#CA2624' : '#7FC83A' ?>;color:<?php echo ($desviacion > 0.5) ? '#FFFFFF' : '#000000' ?>"><?php echo number_format($desviacion, 2); ?>%</td>
                <td><?php echo number_format($detalle["inventario_inicial"], 2); ?></td>
                <td><?php echo number_format($detalle["compras_dia"], 2); ?></td>
                <td><?php echo number_format($incrementoCombustible, 2); ?></td>
                <td><?php echo number_format($detalle["inventario_final"], 2); ?></td>
                <td><?php echo number_format($totalVendidoRoot, 2); ?></td>
                <td><?php echo number_format($detalle["total_vendido_sistema"], 2); ?></td>
                <td><?php echo number_format($diferenciaVendido, 2); ?></td>
                <td style="background-color:<?php echo ($porcentajeMerma > 5) ? '#CA2624' : '#7FC83A' ?>;color:<?php echo ($porcentajeMerma > 5) ? '#FFFFFF' : '#000000' ?>"><?php echo number_format($porcentajeMerma, 2); ?>%</td>
                <td>
                  <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "u") : ?>
                    <button class='btn btn-sm btn-primary' type='button' onclick="eliminar('<?php echo $detalle['idvolumendesviacion']; ?>');"><i class='fas fa-trash fa-sm'></i></button>
                    <?php endif; ?>
                </td>
              </tr>
              <!--Fechas -->
            <?php endforeach;
            if ($totalRegistros) {
              $promedioDesviacion = $sumDesviacion / $totalRegistros;
              $promedioPorcentajeMerma = $sumPorcentajeMerma / $totalRegistros;
            }
            ?>
            <tr>
              <th colspan="9">TOTAL</th>
              <th><?php echo number_format($promedioDesviacion, 2) ?>%</th>
              <th colspan="7"></th>
              <th><?php echo number_format($promedioPorcentajeMerma, 2) ?>%</th>
              <th></th>
            </tr>
            <tr>
              <th>Fecha</th>
              <th>Factura/Remisión</th>
              <th>Volumen factura a 20°C</th>
              <th>Proveedor</th>
              <th>Transporte</th>
              <th>Tanque descarga</th>
              <th>Volumen descarga bruto</th>
              <th>Venta durante descarga</th>
              <th>Volumen desviación Factura vs Veeder root</th>
              <th>Desviación < 0.5% Factura vs Veeder Root</th>
              <th>Inventario Inicial</th>
              <th>Compras del día</th>
              <th>Incremento de combustible existente</th>
              <th>Inventario Final</th>
              <th>Litros vendidos veeder root</th>
              <th>Litros sistema vendidos (I-Gas)</th>
              <th>Diferencia entre veeder root vs I Gas</th>
              <th>Porcentaje de merma < 5%</th>
              <th>Acciones</th>
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
      , filename: 'Reporte sistema gestión de medición-'+zona+'- De '+fechaInicial+' A '+fechaFinal
    });
  });

  $("#zona").change(function() {
    obtenerRutas();
  });

  function obtenerRutas(){
    let tipoUsuario = "<?php echo $_SESSION["tipoUsuario"] ?>";

    let zonaId = 0;
    let zonaTipo = 0;
    if(tipoUsuario == "su" || tipoUsuario == "uc" || tipoUsuario == "inv"){
      zonaId = $("#zona").val(); 
      zonaTipo = $("#zona").find(':selected').data("tipo-zona")
    }
    else{
      zonaId = "<?php echo $_SESSION["zonaId"] ?>";
      zonaTipo = "<?php echo $_SESSION["tipoZona"] ?>";
    }

    $("#ruta").empty().append('<option value="0" disabled>--Selecciona--</option>');

    $.ajax({
      data: { zonaId : zonaId },
      type: "GET",
      url: '../controller/Rutas/ObtenerPorZonaVenta.php', 
      dataType: "json",
      success: function(data){
        $.each(data,function(key, ruta) {
          if((zonaTipo == 1 && (ruta.tipo_ruta_id == 3 || ruta.tipo_ruta_id == 4 || ruta.tipo_ruta_id == 5))//Gas
          || (zonaTipo == 2 && (ruta.tipo_ruta_id == 7 || ruta.tipo_ruta_id == 8 || ruta.tipo_ruta_id == 9))//Gasolina
          ){
            $("#ruta").append('<option value='+ ruta.idruta + '>'+ ruta.clave_ruta +' ('+ruta.capacidad+')</option>');
          }
        });
        let rutaId = "<?php echo $rutaId ?>";  
        $("#ruta").val(rutaId).change();    
      },
      error: function(data) {
        alertify.error('Ha ocurrido un error al cargar las rutas de la zona.');
      }
    });
  }


  $("#ruta").change(function() {
    let rutaId = $("#ruta").val();

    //Obtener precio del producto
    if(rutaId){
    $("#producto").empty().append('<option value="0" disabled>Seleccione Opción</option>');
        $.ajax({
          data: { rutaId : rutaId },
          type: "GET",
          url: '../controller/Rutas/CargarProductosRuta.php', 
          dataType: "json",
          success: function(data){
            $.each(data,function(key, producto) {
              $("#producto").append('<option value='+producto.idproducto+'>'+ producto.nombre +'</option>');
              $("#producto").val(producto.idproducto).change();
            });      
          },
          error: function(data) {
            alertify.error('Ha ocurrido un error al cargar los productos.');
          }
        });
      }
      });

  function eliminar(id) {
    alertify.confirm("¿Realmente desea eliminar el registro?",
        function() {
          $.ajax({
            type: "POST",
            url: "../controller/DesviacionVolumen/Eliminar.php",
            data: {
              id: id
            },
            success: function(data) {
              location.reload();
              alertify.success("Registro eliminado");
            }
          });
        },
        function() {})
      .set({
        title: "Eliminar registro de Gestión de Medición"
      })
      .set({
        labels: {
          ok: 'Aceptar',
          cancel: 'Cancelar'
        }
      });
  }

</script>