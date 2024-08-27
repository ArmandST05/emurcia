<?php
$modelZona = new ModelZona();
$modelClienteDescuento = new ModelClienteDescuento();

if ($_SESSION['tipoUsuario'] == "su" || $_SESSION["tipoUsuario"] == "uc") {
  $zonaId = (!empty($_GET["zonaId"])) ? $_GET["zonaId"] : "";
  $zonas = $modelZona->obtenerTodas();
} else $zonaId = $_SESSION['zonaId'];

if (!empty($zonaId)) {
  $clientes = $modelClienteDescuento->listaZonaEstatus($zonaId,1);
}else $clientes = [];
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Clientes de Descuento</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Clientes de Descuento</h1>
  <?php if ($_SESSION['tipoUsuario'] == "su" || $_SESSION["tipoUsuario"] == "uc") : ?>
    <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" href="index.php?action=clientesdescuento/nuevo.php">Nuevo</a>
  <?php endif; ?>
</div>
<!-- Content Row -->
<div class="row">
  <!-- Nuevo Pedido -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Header -->
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Lista Clientes
          <?php if ($_SESSION['tipoUsuario'] == "su" || $_SESSION["tipoUsuario"] == "uc") : ?>
            <select class="form-control form-control-sm" name="zonaId" id="zonaId" onchange="obtenerClientes()">
              <option value="" readonly>Selecciona zona</option>
              <?php foreach ($zonas as $zonaData) : ?>
                <option value="<?php echo $zonaData['idzona'] ?>" <?php echo ($zonaData['idzona'] == $zonaId) ? "selected" : "" ?>> <?php echo strtoupper($zonaData['nombre']) ?></option>
              <?php endforeach; ?>
            </select>
          <?php endif; ?>
      </div>
      <!-- Card Header -->
      <!-- Card Body -->
      <div class="card-body">
        <?php if (!empty($zonaId)) : ?>
          <div class="row">
            <table id="listaClientes" class="table table-responsive table-bordered table-sm">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Nombre</th>
                  <th>Domicilio</th>
                  <th>Municipio</th>
                  <th>Descuento otorgado</th>
                  <?php if ($_SESSION['tipoUsuario'] == "su" || $_SESSION["tipoUsuario"] == "uc") : ?>
                    <th>Acciones</th>
                  <?php endif; ?>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($clientes as $cliente) : ?>

                  <tr align='center'>
                    <td><?php echo $cliente["idclientedescuento"] ?></td>
                    <td><?php echo $cliente["nombre"] ?></td>
                    <td><?php echo $cliente["calle"]." ".$cliente["numero"].", ".$cliente["colonia"] ?></td>
                    <td><?php echo $cliente["municipio"] ?></td>
                    <td><font color='Blue'><?php echo number_format($cliente["descuento_cantidad"],2) ?></font></td>
                    <?php if ($_SESSION['tipoUsuario'] == "su" || $_SESSION["tipoUsuario"] == "uc") : ?>
                      <td>
                        <button class='btn btn-sm btn-light' type='button' onclick="editar('<?php echo $cliente['idclientedescuento']; ?>');"><i class='fas fa-pencil-alt'></i></button>
                        <button class='btn btn-sm btn-primary' type='button' onclick="actualizarEstatus('<?php echo $cliente['idclientedescuento']; ?>');"><i class='fas fa-trash'></i></button>
                      </td>
                    <?php endif; ?>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
      <!-- Card Body -->
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {});

  $('#listaClientes').DataTable({
    "pageLength": 25,
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
    }
  });

  function obtenerClientes() {
    var zonaId = $("#zonaId").val();
    window.location.href = 'index.php?action=clientesdescuento/index.php&zonaId=' + zonaId;
  }

  function editar(id) {
    window.location.href = 'index.php?action=clientesdescuento/editar.php&id=' + id;
  }

  function actualizarEstatus(id) {
    alertify.confirm("¿Realmente desea eliminar el cliente seleccionado?",
        function() {
          $.ajax({
            type: "POST",
            url: "../controller/ClientesDescuento/ActualizarEstatus.php",
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