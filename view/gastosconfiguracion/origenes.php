<?php
$modelOrigenGasto = new ModelOrigenGasto();
$origenes = $modelOrigenGasto->index();
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Gastos</a> /
    <a href="index.php?action=gastosconfiguracion/index.php">Configuración</a> /
    <a href="#">Orígenes</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Orígenes</h1>
  <button class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" onclick="abrirmodalNuevoOrigen()">Nuevo</button>
</div>
<!-- Content Row -->
<div class="row">
  <!-- Nuevo -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Header -->
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Lista Orígenes</h6>
      </div>
      <!-- Card Body -->
      <div class="card-body">
        <div class="row">
          <table id="listaTabla" class="table table-bordered table-sm table-responsive" style="width:100%">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Estatus</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($origenes as $claveOrigen => $origen) : ?>
                <tr>
                  <td><?php echo $origen["nombre"] ?></td>
                  <td id="estatusOrigen<?php echo $origen["idorigengasto"] ?>"><?php echo ($origen["estatus"] == 1) ? "ACTIVO" : "INACTIVO" ?></td>
                  <td>
                    <button class="btn btn-light btn-sm" type="button" onclick="abrirModalEditarOrigen('<?php echo $origen['idorigengasto'] ?>','<?php echo $origen['nombre'] ?>');" data-toggle="tooltip" title="Editar"><i class="fas fa-pencil-alt"></i></button>
                    <button class="btn btn-sm btn-primary" type='button' <?php echo ($origen["estatus"] == 0) ? "style='display:none'" : "" ?> id="desactivarOrigen<?php echo $origen["idorigengasto"] ?>" onclick="desactivarOrigen('<?php echo $origen['idorigengasto']; ?>');" data-toggle="tooltip" title="Desactivar"><i class='fas fa-trash fa-sm'></i></button>
                    <button class="btn btn-sm btn-warning" type='button' <?php echo ($origen["estatus"] == 1) ? "style='display:none'" : "" ?> id="activarOrigen<?php echo $origen["idorigengasto"] ?>" onclick="activarOrigen('<?php echo $origen['idorigengasto']; ?>');" data-toggle="tooltip" title="Activar"><i class='fas fa-trash-restore fa-sm'></i></button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Nuevo Origen -->
  <div class="modal fade" id="modalNuevoOrigen" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <form method="POST" action="../controller/OrigenesGasto/Insertar.php">
          <div class="modal-header">
            <h5 class="modal-title">Nuevo Origen<h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-2">
                <label>Nombre:</label>
              </div>
              <div class="col-md-10">
                <input type="text" class="form-control form-control-sm" name="nombre" id="nuevoOrigenNombre">
              </div>
            </div>
            <br>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-sm btn-primary"></i>Guardar</button>
          </div>
      </div>
      </form>
    </div>
  </div>
  <!-- Modal Nuevo Origen -->
  <!-- Modal Editar Origen -->
  <div class="modal fade" id="modalEditarOrigen" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <form method="POST" action="../controller/OrigenesGasto/Actualizar.php">
          <div class="modal-header">
            <h5 class="modal-title">Editar Origen<h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-2">
                <label>Nombre:</label>
              </div>
              <div class="col-md-10">
                <input type="text" class="form-control form-control-sm" name="nombre" id="editarOrigenNombre">
              </div>
            </div>
            <br>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="id" id="editarOrigenId" required>
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-sm btn-primary"></i>Guardar</button>
          </div>
      </div>
      </form>
    </div>
  </div>
  <!-- Modal Editar Origen -->

  <script type="text/JavaScript">
    $(document).ready(function(){
    });

    function abrirmodalNuevoOrigen() {
      $("#nuevoOrigenNombre").val();
      $("#modalNuevoOrigen").modal({
        show: true,
        keyboard: false,
        backdrop: 'static'
      });
    }

    function abrirModalEditarOrigen(id,nombre) {
      $("#editarOrigenId").val(id);
      $("#editarOrigenNombre").val(nombre);
      $("#modalEditarOrigen").modal({
        show: true,
        keyboard: false,
        backdrop: 'static'
      });
    }

  function activarOrigen(id) {
    var id = id;
    $.ajax({
      type: "POST",
      url: "../../controller/OrigenesGasto/Activar.php",
      data: {
        id: id
      },
      success: function(data) {
        $("#estatusOrigen" + id).text("ACTIVO");
        $("#desactivarOrigen" + id).show();
        $("#activarOrigen" + id).hide();
        alertify.success("Activación de origen exitosa");
      }
    });
  }

  function desactivarOrigen(id) {
    var id = id;
    $.ajax({
      type: "POST",
      url: "../../controller/OrigenesGasto/Desactivar.php",
      data: {
        id: id
      },
      success: function(data) {
        $("#estatusOrigen" + id).text("INACTIVO");
        $("#activarOrigen" + id).show();
        $("#desactivarOrigen" + id).hide();
        alertify.message("Desactivación de origen exitosa");
      }
    });
  }
</script>