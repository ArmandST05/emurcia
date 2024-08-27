<?php
$modelCompania = new ModelCompania();
$modelZona = new ModelZona();
$modelPedido = new ModelPedido();

$tiposTransacciones = $modelPedido->listaTiposTransacciones();

$companiaId = null;
$estatusId = (!empty($_GET['estatusId'])) ? $_GET['estatusId'] : "1";
$clienteId = (!empty($_GET['clienteId'])) ? $_GET['clienteId'] : "1";
$tipoTransaccionId = (!empty($_GET['tipoTransaccionId'])) ? $_GET['tipoTransaccionId'] : "1";
$fechaInicial = (!empty($_GET['fechaInicial'])) ? $_GET['fechaInicial'] : date("Y-m-d");
$fechaFinal = (!empty($_GET['fechaFinal'])) ? $_GET['fechaFinal'] : date("Y-m-d");

if ($_SESSION['tipoUsuario'] == "su" || $_SESSION["tipoUsuario"] == "uc") {
  $companiaId = (!empty($_GET['companiaId'])) ? $_GET['companiaId'] : "0";
  $zonaId = (!empty($_GET["zonaId"])) ? $_GET["zonaId"] : "0";

  $companias = $modelCompania->listaPorEstatus(1);
} else {
  $zonaId = $_SESSION['zonaId'];
}

if (!empty($zonaId) || !empty($companiaId)) {
  $transaccionesPuntos = $modelPedido->obtenerTransaccionesPuntosZona($zonaId, $tipoTransaccionId, $fechaInicial, $fechaFinal);
} else $transaccionesPuntos = [];

?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Sistema puntos</a> /
    <a href="#">Reporte puntos</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Reporte puntos</h1>
</div>
<!-- Content Row -->
<div class="row">
  <!-- Nuevo Pedido -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Header -->
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Buscar<br>
      </div>
      <!-- Card Header -->
      <!-- Card Body -->
      <div class="card-body">
        <form method="GET" action="index.php">
          <div class="row">
            <?php if ($_SESSION['tipoUsuario'] == "su" || $_SESSION["tipoUsuario"] == "uc") : ?>
              <div class="col-md-3">
                <label>Compañía:</label>
                <select class="form-control form-control-sm" name="companiaId" id="companiaId" onchange="obtenerZonas()">
                  <option value="0">--TODAS--</option>
                  <?php foreach ($companias as $compania) : ?>
                    <option value="<?php echo $compania['idcompania'] ?>" <?php echo ($compania['idcompania'] == $companiaId) ? "selected" : "" ?>> <?php echo strtoupper($compania['nombre']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-2">
                <label>Zona:</label>
                <select class="form-control form-control-sm" name="zonaId" id="zonaId">
                  <option value="0">--TODAS--</option>
                </select>
              </div>
              <div class="col-md-2">
                <label>Tipo:</label>
                <select class="form-control form-control-sm" name="tipoTransaccionId" id="tipoTransaccionId">
                  <?php foreach ($tiposTransacciones as $tipoTransaccion) : ?>
                    <option value="<?php echo $tipoTransaccion['idtipotransaccionpuntos'] ?>" <?php echo ($tipoTransaccion['idtipotransaccionpuntos'] == $tipoTransaccionId) ? "selected" : "" ?>> <?php echo strtoupper($tipoTransaccion['nombre']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-2">
                <label>Desde:</label>
                <input class="form-control form-control-sm" type="date" id="fechaInicial" name="fechaInicial" value="<?php echo $fechaInicial ?>">

              </div>
              <div class="col-md-2">
                <label>Hasta:</label>
                <input class="form-control form-control-sm" type="date" id="fechaFinal" name="fechaFinal" value="<?php echo $fechaFinal ?>">
              </div>
            <?php endif; ?>
            <!--
            <div class="col-md-3">
              <label>Clientes:</label>
              <select class="form-control form-control-sm" name="clienteId" id="clienteId">
                <option value="0">--TODOS--</option>
              </select>
            </div>-->
            <div class="col-md-1">
              <br>
              <input type="hidden" name="action" value="sistema-puntos/reporte-canjeados.php"></input>
              <button type="submit" class="btn btn-sm btn-primary">Buscar</button>
            </div>
          </div>
        </form>
        <!-- Card Body -->
      </div>
    </div>
  </div>
</div>
<!-- Content Row -->
<div class="row">
  <!-- Nuevo Pedido -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Header -->
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Puntos canjeados<br>

      </div>
      <!-- Card Header -->
      <!-- Card Body -->
      <div class="card-body">
        <div class="row">
          <table id="listaTabla" class="table table-responsive table-bordered table-sm">
            <thead>
              <tr>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Puntos canjeados</th>
              </tr>
            </thead>
            <?php if (!empty($zonaId) || !empty($companiaId)) : ?>
              <tbody>
                <?php foreach ($transaccionesPuntos as $transaccionPunto) :
                ?>
                  <tr align='center'>
                    <td><?php echo $transaccionPunto["fecha_pedido"] ?></td>
                    <td><?php echo $transaccionPunto["cliente_nombre"] ?></td>
                    <td><?php echo $transaccionPunto["cantidad_puntos"] ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            <?php endif; ?>
          </table>
        </div>

      </div>
      <!-- Card Body -->
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $("#zonaId").select2({});
    $("#clienteId").select2({});
    obtenerZonas();
  });

  $('#listaTabla').DataTable({
    "pageLength": 25,
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
    }
  });

  function obtenerZonas() {
    let companiaId = $("#companiaId").val();

    $("#zonaId").empty().append('<option value="0" selected>TODAS</option>');

    $.ajax({
      data: {
        companiaId: companiaId,
        estatusId: "<?php echo $estatusId ?>"
      },
      type: "GET",
      url: '../controller/Zonas/ObtenerPorCompania.php',
      dataType: "json",
      success: function(data) {
        $.each(data, function(key, zona) {
          let selected = "";
          if ("<?php echo $zonaId ?>" == zona.idzona) {
            selected = "selected"
          }
          $("#zonaId").append('<option value=' + zona.idzona + ' ' + selected + '>' + zona.nombre + '</option>');
        });
      },
      error: function(data) {
        alertify.error('Ha ocurrido un error al cargar las zonas');
      }
    });
    obtenerClientes();
  }

  function obtenerClientes() {
    let companiaId = $("#companiaId").val();
    let zonaId = $("#zonaId").val();
    /*
        $("#clienteId").empty().append('<option value="0" selected>TODOS</option>');

        $.ajax({
          data: {
            companiaId: companiaId,
            zonaId: zonaId,
            estatusId: 1
          },
          type: "GET",
          url: '../controller/ClientesPuntos/ObtenerPorZona.php',
          dataType: "json",
          success: function(data) {
            $.each(data, function(key, cliente) {
              let selected = "";
              if ("<?php echo $clienteId ?>" == cliente.id) {
                selected = "selected"
              }
              $("#clienteId").append('<option value=' + cliente.id + ' ' + selected + '>' + cliente.nombre + '</option>');
            });
          },
          error: function(data) {
            alertify.error('Ha ocurrido un error al cargar los clientes');
          }
        });*/
  }

  function editar(id) {
    window.location.href = 'index.php?action=clientes-puntos/editar.php&id=' + id;
  }

  function actualizarEstatus(id) {
    alertify.confirm("¿Realmente desea eliminar el cliente seleccionado?",
        function() {
          $.ajax({
            type: "POST",
            url: "../controller/ClientesPunto/ActualizarEstatus.php",
            data: {
              id: id,
              estatusId: 0
            },
            success: function(data) {
              location.reload();
              alertify.success("Cliente eliminado exitosamente");
            }
          });
        },
        function() {})
      .set({
        title: "Eliminar cliente"
      })
      .set({
        labels: {
          ok: 'Aceptar',
          cancel: 'Cancelar'
        }
      });
  }
</script>