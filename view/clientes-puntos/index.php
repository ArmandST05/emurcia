<?php
$modelCompania = new ModelCompania();
$modelZona = new ModelZona();
$modelClientePedido = new ModelClientePedido();

$estatusId = (!empty($_GET['estatusId'])) ? $_GET['estatusId'] : "1";

if ($_SESSION['tipoUsuario'] == "su" || $_SESSION["tipoUsuario"] == "uc") {
  $companiaId = (!empty($_GET['companiaId'])) ? $_GET['companiaId'] : "0";
  $zonaId = (!empty($_GET["zonaId"])) ? $_GET["zonaId"] : "0";

  $zonas = $modelZona->obtenerZonasGas();

  $companias = $modelCompania->listaPorEstatus(1);
} else $zonaId = $_SESSION['zonaId'];

$clientes = $modelClientePedido->listaClientesPuntosZona($zonaId);

?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Clientes puntos</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Clientes puntos</h1>
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
              <!--<div class="col-md-3">
                <label>Compañía:</label>
                <select class="form-control form-control-sm" name="companiaId" id="companiaId" onchange="obtenerZonas()">
                  <option value="0">--TODAS--</option>
                  <?php foreach ($companias as $compania) : ?>
                    <option value="<?php echo $compania['idcompania'] ?>" <?php echo ($compania['idcompania'] == $companiaId) ? "selected" : "" ?>> <?php echo strtoupper($compania['nombre']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>-->
              <div class="col-md-3">
                <label>Zona:</label>
                <select class="form-control form-control-sm" name="zonaId" id="zonaId" onchange="obtenerZonas()">
                  <option value="0">SIN ZONA ASIGNADA</option>
                  <?php foreach ($zonas as $zona) : ?>
                    <option value="<?php echo $zona['idzona'] ?>" <?php echo ($zona['idzona'] == $zonaId) ? "selected" : "" ?>> <?php echo strtoupper($zona['nombre']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            <?php endif; ?>
            <!--<div class="col-md-2">
              <label>Estatus:</label>
              <select class="form-control form-control-sm" name="estatusId" id="estatusId">
                <option value="1" <?php echo ("1" == $estatusId) ? "selected" : "" ?>>ACTIVO</option>
                <option value="0" <?php echo ("0" == $estatusId) ? "selected" : "" ?>>INACTIVO</option>
              </select>
            </div>-->
            <div class="col-md-1">
              <br>
              <input type="hidden" name="action" value="clientes-puntos/index.php"></input>
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
        <h6 class="m-0 font-weight-bold text-primary">Lista Clientes<br>

      </div>
      <!-- Card Header -->
      <!-- Card Body -->
      <div class="card-body">
          <div class="row">
            <table id="listaClientes" class="table table-responsive table-bordered table-sm">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Nombre</th>
                  <th>Teléfono</th>
                  <th>Direcciones</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($clientes as $cliente) :
                  $direcciones = $modelClientePedido->listaDireccionesClientePuntosZona($cliente["idclientepedido"],$zonaId);
                ?>
                  <tr align='center'>
                    <td><?php echo $cliente["idclientepedido"] ?></td>
                    <td><?php echo $cliente["nombre"] ?></td>
                    <td><?php echo $cliente["telefono"] ?></td>
                    <td><?php foreach($direcciones as $direccion){
                      echo $direccion["calle"] . " " . $direccion["numero_exterior"]. " " . $direccion["numero_interior"] . ", " . $direccion["localidad_nombre"]. ", " . $direccion["municipio_nombre"]. ", " . $direccion["estado_nombre"]."<br>";
                      }?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
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
    //obtenerZonas();
  });

  $('#listaClientes').DataTable({
    "pageLength": 25,
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
    }
  });

  /*function obtenerZonas() {
    console.log("obtener");
    let companiaId = $("#companiaId").val();

    $("#zonaId").empty().append('<option value="0" selected>TODAS</option>');

    $.ajax({
      data: {
        companiaId: companiaId,
        estatusId:"<?php echo $estatusId?>"
      },
      type: "GET",
      url: '../controller/Zonas/ObtenerPorCompania.php',
      dataType: "json",
      success: function(data) {
        $.each(data, function(key, zona) {
          let selected = "";
          if("<?php echo $zonaId ?>" == zona.idzona){
            selected = "selected"
          }
          $("#zonaId").append('<option value=' + zona.idzona +' '+selected+ '>' + zona.nombre + '</option>');
        });
      },
      error: function(data) {
        alertify.error('Ha ocurrido un error al cargar las zonas');
      }
    });
  }*/

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