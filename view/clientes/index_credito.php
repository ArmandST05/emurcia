<?php
$modelZona = new ModelZona();
$modelCliente = new ModelCliente();
$modelZona = new ModelZona();

if ($_SESSION['tipoUsuario'] == "su" || $_SESSION["tipoUsuario"] == "uc") {
  $zona = (!empty($_GET["zona"])) ? $_GET["zona"] : "";
  $zonas = $modelZona->obtenerTodas();
} else $zona = $_SESSION['zonaId'];

$otor = 0;
if (!empty($zona)) {
  $clientes = $modelCliente->obtenerClientes($zona);
  $saldo = $modelCliente->obtenerSaldoZonasOtorgados($zona);
  foreach ($saldo as $key) {
    $otor = $key["otorgados"];
  }
}
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Clientes de Crédito</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Clientes de Crédito</h1>
  <?php if ($_SESSION['tipoUsuario'] == "su" || $_SESSION["tipoUsuario"] == "uc") : ?>
    <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" href="index.php?action=clientes/nuevo_credito.php">Nuevo</a>
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
            <select class="form-control form-control-sm" name="zona" id="zona" onchange="obtenerClientes()">
              <option value="" readonly>Selecciona zona</option>
              <?php foreach ($zonas as $zonaData) : ?>
                <option value="<?php echo $zonaData['idzona'] ?>" <?php echo ($zonaData['idzona'] == $zona) ? "selected" : "" ?>> <?php echo strtoupper($zonaData['nombre']) ?></option>
              <?php endforeach; ?>
            </select>
          <?php endif; ?>
      </div>
      <!-- Card Header -->
      <!-- Card Body -->
      <div class="card-body">
        <?php if (!empty($zona)) : ?>
          <div class="row">
            <table>
              <tr>
                <td>Saldo:</td>
                <td><b>$<?php echo $otor; ?></b></td>
              </tr>
            </table>
            <table id="listaClientes" class="table table-responsive table-bordered table-sm">
              <thead>
                <tr>
                  <th>Número cliente</th>
                  <th>Nombre</th>
                  <th>Domicilio</th>
                  <th>Colonia</th>
                  <th>Nombre comercial</th>
                  <th>Límite de crédito</th>
                  <th>Crédito otorgado</th>
                  <th>Crédito disponible</th>
                  <th>Precio con descuento</th>
                  <?php if ($_SESSION['tipoUsuario'] == "su" || $_SESSION["tipoUsuario"] == "uc") : ?>
                    <th>Acciones</th>
                  <?php endif; ?>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($clientes as $cliente) : ?>

                  <tr align='center'>
                    <td><?php echo $cliente["num_cliente"] ?></td>
                    <td><?php echo $cliente["nombre_cliente"] ?></td>
                    <td><?php echo $cliente["domicilio"] ?></td>
                    <td><?php echo $cliente["colonia"] ?></td>
                    <td><?php echo $cliente["nombre_comercial"] ?></td>
                    <td><b>$<?php echo $cliente["credit_otor"] ?></b></td>
                    <td>$<?php echo $cliente["credit_use"] ?></td>
                    <td>
                      <b>
                        <font color='Blue'>$<?php echo $cliente["credit_actual"] ?></font>
                      </b>
                    </td>
                    <td>$<?php echo $cliente["precio_des"] ?></td>
                    <?php if ($_SESSION['tipoUsuario'] == "su" || $_SESSION["tipoUsuario"] == "uc") : ?>
                      <td>
                        <button class='btn btn-sm btn-light' type='button' onclick="editar('<?php echo $cliente['num_cliente']; ?>');"><i class='fas fa-pencil-alt'></i></button>
                        <button class='btn btn-sm btn-primary' type='button' onclick="eliminar('<?php echo $cliente['num_cliente']; ?>');"><i class='fas fa-trash'></i></button>
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
    var zona = $("#zona").val();
    window.location.href = 'index.php?action=clientes/index_credito.php&zona=' + zona;
  }

  function editar(id) {
    window.location.href = 'index.php?action=clientes/editar_credito.php&id=' + id;
  }

  function eliminar(id) {
    alertify.confirm("¿Realmente desea eliminar el cliente seleccionado?",
        function() {
          $.ajax({
            type: "POST",
            url: "../controller/Clientes/EliminarClienteCredito.php",
            data: {
              id: id
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