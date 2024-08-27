<?php
// Crear una instancia del modelo de empleados
$modelEmpleado = new ModelEmpleado();

// Obtener la lista de empleados


$modelZona = new ModelZona();
if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "no") {
  $zonaId = (isset($_GET["zona"])) ? $_GET["zona"] : 0;
  $estatus = (isset($_GET["estatus"])) ? $_GET["estatus"] : 1;
  $zonas = $modelZona->listaTodas();
} else {
  $zona = $_SESSION["zona"];
  $zonaId = $_SESSION["zonaId"];
}

$empleados = $modelEmpleado->obtenerEmpleadosZonaEstatus($zonaId, $estatus);
?>


<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Empleados</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Empleados</h1>
  <?php if ($_SESSION['tipoUsuario'] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "no") : ?>
    <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" href="index.php?action=empleados/nuevo.php">Nuevo</a>
  <?php endif; ?>
</div>

<?php if ($_SESSION['tipoUsuario'] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "no") : ?>
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
          <form>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Zona:</label>
                  <select class="form-control form-control-sm" name="zona" id="zonaselect">
                    <option value="" readonly value="0">Todas</option>
                    <?php foreach ($zonas as $zonaData) : ?>
                      <option value="<?php echo $zonaData['idzona'] ?>" <?php echo ($zonaData['idzona'] == $zonaId) ? "selected" : "" ?>>
                        <?php echo strtoupper($zonaData['nombre']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label>Estatus:</label>
                  <select name="estatus" class="form-control form-control-sm">
                    <option value="1" <?php echo ($estatus == 1) ? "selected" : "" ?>>Activos</option>
                    <option value="0" <?php echo ($estatus == 0) ? "selected" : "" ?>>Inactivos</option>
                  </select>

                </div>
              </div>
              <div class="col-md-1">
                <br>
                <div class="form-group">
                  <button type="submit" class="btn btn-sm btn-primary shadow-sm" id="btnBuscarEmpleado" name="btnBuscarEmpleado">Buscar</button>
                  <input type='hidden' name='action' id='action' value="empleados/index.php" />
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>


<div class="row">
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Lista de Empleados</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-sm" id="listaEmpleados">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Puesto</th>
                <th>Sueldo base</th>
                <th>Infonavit</th>
                <th>Zona</th>
                <th>Estatus</th>
                <th>Acción</th>
              </tr>
            </thead>

            <tbody>
              <?php foreach ($empleados as $empleado) : ?>
                <tr>
                  <td>
                    <?php echo $empleado['idempleado']; ?>
                  </td>
                  <td>
                    <?php echo $empleado['nombre']; ?>
                  </td>
                  <td>
                    <?php
                    echo $empleado['tipo_empleado'];
                    ?>
                  </td>
                  <td>
                    <?php echo $empleado["sueldo_base"] ?>
                  </td>
                  <td>
                    <?php echo $empleado["infonavit"] ?>
                  </td>
                  <td>
                    <?php echo $empleado["zona_nombre"] ?>
                  </td>
                  <td>
                    <?php echo ($empleado["estatus"]) ? "Activo" : "Inactivo" ?>
                  </td>
                  <td>
                    <a class="btn btn-sm btn-light" data-toggle="tooltip" title="Editar" href="index.php?action=empleados/editar.php&id=<?php echo $empleado['idempleado']; ?>">
                      <i class="fas fa-pencil-alt"></i></a>
                    <button class="btn btn-sm btn-primary eliminar" type="button" data-toggle="tooltip" title="Eliminar" onclick="confirmarEliminarEmpleado(<?php echo $empleado['idempleado']; ?>)">
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {

    $('#listaEmpleados').DataTable({
      "pageLength": 25,
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
      }
    });

  });


  function confirmarEliminarEmpleado(id) {

    alertify.confirm("¿Realmente desea eliminar el empleado seleccionado?",
        function() {
          $.ajax({
            type: "POST",
            url: "../controller/Empleados/EliminarEmpleado.php",
            data: {
              id: id
            },
            success: function(data) {
              location.reload();
              alertify.success("Empleado marcado como inactivo");
            },
            error: function(xhr, status, error) {
              alertify.error("Error al marcar como inactivo al empleado: " + error);
            }
          });
        },
        function() {})
      .set({
        title: "Eliminar empleado"
      })
      .set({
        labels: {
          ok: 'Aceptar',
          cancel: 'Cancelar'
        }
      });
  }

  function BuscarZona() {
    let zonaId = $("#zonaselect").val();
    window.location.href = "index.php?action=empleados/index.php&zona=" + zonaId;
  }
</script>