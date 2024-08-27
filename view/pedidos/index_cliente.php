<?php
$modelZona = new ModelZona();
$modelPedido = new ModelPedido();
$modelClientePedido = new ModelClientePedido;
date_default_timezone_set('America/Mexico_City');

$meses = [
  ["id" => "01", "nombre" => "Enero"],
  ["id" => "02", "nombre" => "Febrero"],
  ["id" => "03", "nombre" => "Marzo"],
  ["id" => "04", "nombre" => "Abril"],
  ["id" => "05", "nombre" => "Mayo"],
  ["id" => "06", "nombre" => "Junio"],
  ["id" => "07", "nombre" => "Julio"],
  ["id" => "08", "nombre" => "Agosto"],
  ["id" => "09", "nombre" => "Septiembre"],
  ["id" => "10", "nombre" => "Octubre"],
  ["id" => "11", "nombre" => "Noviembre"],
  ["id" => "12", "nombre" => "Diciembre"],
];

$tipoBusqueda = (!empty($_GET["tipoBusqueda"])) ? $_GET["tipoBusqueda"] : "todos";
$fechaInicial = (!empty($_GET['fechaInicial'])) ? $_GET['fechaInicial'] : "";
$fechaFinal = (!empty($_GET['fechaFinal'])) ? $_GET['fechaFinal'] : "";

$clienteId = $_GET['clienteId'];
$cliente = $modelClientePedido->obtenerPorId($clienteId);
$pedidos = $modelPedido->listaPedidosCliente($clienteId);

if (!$cliente) {
  echo "<script> 
    alert('El cliente no existe');
    window.location.href = 'index.php?action=clientes/index_pedido.php';
    </script>";
}

if ($fechaInicial && $fechaFinal) $totalesPedidos = $modelPedido->obtenerTotalesPedidosClienteFechas($fechaInicial, $fechaFinal, $clienteId);
else {
  $totalesPedidos = $modelPedido->obtenerTotalesPedidosCliente($clienteId);
  $fechaInicial = date("Y-m-d");
  $fechaFinal = date("Y-m-d");
}

if ($_SESSION["tipoUsuario"] == "su") {
  $zonaId = $_GET['zona'];
  $zonas = $modelZona->obtenerZonasTodas();
} else {
  $zonaId = $_SESSION['zonaId'];
}

$mesesData2 = ["1" => "Enero", "2" => "Febrero", "3" => "Marzo", "4" => "Abril", "5" => "Mayo", "6" => "Junio", "7" => "Julio", "8" => "Agosto", "9" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];

$anio = date("Y");
$precioLts = 0;
$precioKg = 0;
$productos = [];

//Obtener precio mes actual para los litros de gas
$mes = $mesesData2[date("m")];
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="index.php?action=clientes/index_pedido.php">Clientes</a>/
    <a href="#">Historial Pedidos</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Historial <?php echo $cliente['nombre'] ?></h1>
</div>
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
            <div class="col-md-2">
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="tipoBusqueda" id="tipoBusqueda" value="todos" <?php echo ($tipoBusqueda == "todos") ? "checked" : "" ?>>
                <label class="form-check-label">Todos</label>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="tipoBusqueda" id="tipoBusqueda" value="fechas" <?php echo ($tipoBusqueda == "fechas") ? "checked" : "" ?>>
                <label class="form-check-label">Por Fechas</label>
              </div>
            </div>
          </div>
          <div class="row" id="fechas">
            <div class="col-md-2">
              <input class="form-control form-control-sm" type="date" name="fechaInicial" value="<?php echo $fechaInicial ?>">
            </div>
            <div class="col-md-2">
              <input class="form-control form-control-sm" type="date" name="fechaFinal" value="<?php echo $fechaFinal ?>">
            </div>
            <div class="col-md-1">
              <input type="hidden" name="action" value="pedidos/index_cliente.php"></input>
              <input type="hidden" name="clienteId" value="<?php echo $clienteId; ?>"></input>
              <button type="submit" class="btn btn-sm btn-primary">Buscar</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Content Row -->
<div class="row">
  <!-- Lista de totales -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Header -->
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Totales</h6>
      </div>
      <!-- Card Body -->
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <div class="row">
              <div class="col-md-6">
                Total Pedidos:
              </div>
              <div class="col-md-6">
                <?php echo $totalesPedidos['total_pedidos'] ?>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="row">
              <div class="col-md-6">
                Pedidos Atendidos:
              </div>
              <div class="col-md-6">
                <?php echo $totalesPedidos['total_atendidos'] ?>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                Pedidos Programados:
              </div>
              <div class="col-md-6">
                <?php echo $totalesPedidos['total_programados'] ?>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                Pedidos Cancelados:
              </div>
              <div class="col-md-6">
                <?php echo $totalesPedidos['total_cancelados'] ?>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="row">
              <div class="col-md-6">
                Contactados Llamadas:
              </div>
              <div class="col-md-6">
                <?php echo $totalesPedidos['total_contacto_llamadas'] ?>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                Contactados Ruteo/Perifoneo:
              </div>
              <div class="col-md-6">
                <?php echo $totalesPedidos['total_contacto_ruteo'] ?>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                Contactados App:
              </div>
              <div class="col-md-6">
                <?php echo $totalesPedidos['total_contacto_app'] ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Lista de totales -->
  </div>
  <!-- Content Row -->
  <div class="row">
    <!-- Lista de Pedidos -->
    <div class="col-xl-12 col-lg-12">
      <div class="card shadow mb-4">
        <!-- Card Header -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Lista Pedidos</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
          <div class="row">
            <div class="col-md-2 offset-md-10">
              <button class="btn btn-sm btn-warning" id="btnExport"><i class="far fa-file-excel"></i> Exportar Excel</button>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col">
              <table id="listaTabla" class="table table-bordered table-sm table-responsive">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Ruta</th>
                    <th>Cliente</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Fracc/Colonia</th>
                    <th>Comentarios</th>
                    <th>Producto</th>
                    <th>Total Kg/Lts</th>
                    <th>Folio Nota</th>
                    <th>Estatus</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($pedidos) :
                    foreach ($pedidos as $clave => $pedido) : ?>
                      <tr>
                        <td><?php echo $pedido["idpedido"] ?></td>
                        <td><?php echo $pedido["fecha_pedido"] ?></td>
                        <td><?php echo $pedido["hora_pedido"] ?></td>
                        <td><b><?php echo $pedido["ruta_nombre"] ?></b><br><?php echo $pedido["vendedor_nombre"] ?></td>
                        <td><b><?php echo strtoupper($pedido["cliente_nombre"]) ?></b><br><?php echo $pedido["tipo_contacto_nombre"] ?></td>
                        <td><?php echo $pedido["cliente_telefono"] ?></td>
                        <td><?php echo $pedido["direccion"] ?></td>
                        <td><?php echo $pedido["fracc_col"] ?></td>
                        <td id="comentario<?php echo $pedido["idpedido"] ?>"><?php echo $pedido["comentario"] ?></td>
                        <td><?php echo $pedido["producto_nombre"] ?></td>
                        <td><?php echo $pedido["total_kg_lts"] ?></td>
                        <td><?php echo $pedido["folio_venta"] ?></td>
                        <td><?php echo $pedido["estatus_pedido_nombre"] ?></td>
                      </tr>
                  <?php endforeach;
                  endif; ?>
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
      if("<?php echo $tipoBusqueda; ?>" == "fechas") $("#fechas").show();
      else $("#fechas").hide();
    });

    $(':radio[name="tipoBusqueda"]').click (function () {
      if($(this).val() == "fechas") $("#fechas").show();
      else $("#fechas").hide();
    });

    $('#listaTabla').DataTable({
      pageLength: 100,
      language: {
        url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
      }
    });

    $("#btnExport").click(function (e) {
      $("#listaTabla").btechco_excelexport({
        containerid: "listaTabla"
        , datatype: $datatype.Table
        , filename: 'Historial-Cliente'
      });
    });
</script>