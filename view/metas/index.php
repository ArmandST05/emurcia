<?php
$modelMeta = new ModelMeta();

$modelZona = new ModelZona();
if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "no" || $_SESSION["tipoUsuario"] == "uc") {
  $zonaId = (isset($_GET["zona"])) ? $_GET["zona"] : "";
  $zonas = $modelZona->obtenerZonasGas();
} else {
  $zona = $_SESSION["zona"];
  $zonaId = $_SESSION["zonaId"];
}
$metas = $modelMeta->obtenerMetasPorZona($zonaId);
?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="../view/index.php?action=metas/index.php">Metas por zona</a>
  </div>
</div>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Metas y comisiones</h1>
  <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" href="index.php?action=metas/nuevo.php">Nueva</a>
</div>


<?php if ($_SESSION['tipoUsuario'] == "su" || $_SESSION["tipoUsuario"] == "no" || $_SESSION["tipoUsuario"] == "uc") : ?>
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
              <div class="col-md-1">
                <div class="form-group">
                  <label>Zona:</label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <select class="form-control form-control-sm" name="zona" id="zona">
                    <option value="" readonly value="0">SELECCIONAR ZONA</option>
                    <?php foreach ($zonas as $zonaData) : ?>
                      <option value="<?php echo $zonaData['idzona'] ?>" <?php echo ($zonaData['idzona'] == $zonaId) ? "selected" : "" ?>>
                        <?php echo strtoupper($zonaData['nombre']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div clas="col-md-2">
                <a class="btn btn-primary btn-sm" id="seleccionarZona">Buscar</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<!-- tabla para mostrar metas por zona -->
<div class="row">
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Metas por zona</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <div id="resultadosBusqueda">

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('#zona').select2({});
    
    $('#seleccionarZona').click(function() {
      // Obtener el valor seleccionado en el menú desplegable
      var zonaSeleccionada = $('#zona').val();

      // Realizar una solicitud AJAX al servidor
      $.ajax({
        type: 'GET',
        url: '../controller/Metas/ObtenerMetasPorZona.php', // 
        data: {
          zona: zonaSeleccionada
        },
        success: function(response) {
          // Actualizar el contenido de la tabla con los resultados
          $('#resultadosBusqueda').html(response);
        },
        error: function() {
          // Manejar errores si es necesario
          alert('Hubo un error al cargar los resultados.');
        }
      });
    });
  });

  function editarMeta(idmeta) {
    window.location.href = 'index.php?action=metas/editar.php&id=' + idmeta;
  }

  function confirmarEliminarMeta(id) {
    alertify.confirm("¿Realmente desea eliminar la meta seleccionada?",
        function() {
          $.ajax({
            type: "POST",
            url: "../controller/Metas/EliminarMeta.php",
            data: {
              id: id
            },
            success: function(data) {
              location.reload();
              alertify.success("Se ha eliminado la meta.  \n Asegúrate de que todo esté correcto ya que estos registros son necesarios para calcular tu nómina");
            },
            error: function(xhr, status, error) {
              alertify.error("Error al eliminar la meta: " + error);
            }
          });
        },
        function() {})
      .set({
        title: "Eliminar meta"
      })
      .set({
        labels: {
          ok: 'Aceptar',
          cancel: 'Cancelar'
        }
      });
  }
</script>