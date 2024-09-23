<?php
$modelZona = new ModelZona();
$modelVenta = new ModelVenta();
$modelRuta = new ModelRuta();
$modelProducto = new ModelProducto();
$modelNomina = new ModelNomina();

//Búsqueda de datos
$fechaMinima = date(("Y-m-d"), strtotime("-7 days"));
$mesInicial = (isset($_GET["mesInicial"])) ? $_GET["mesInicial"] : date("Y-m");

$zonas = $modelZona->obtenerZonasGas();
$zonaId = null;

if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "no" || $_SESSION["tipoUsuario"] == "uc") {
  $zonaId = (!empty($_GET["zona"])) ? $_GET["zona"] : null;
}

$nominas = $modelNomina->obtenerNominasZonaMes($zonaId, $mesInicial);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Obtén el valor del formulario
  $valor_fondo = $_POST['valor_fondo'];

  // Llama a la función insertarValorFondo del modelo
  $result = $modelNomina->insertarValorFondo($valor_fondo);

  // Opcional: Mostrar un mensaje o manejar el resultado
  if ($result) {
      echo "<p>Fondo de ahorro guardado exitosamente.</p>";
  } else {
      echo "<p>Error al guardar el fondo de ahorro.</p>";
  }
}
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Nómina</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Nómina</h1>
  <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION['tipoUsuario'] == "no" || $_SESSION["tipoUsuario"] == "uc") : ?>
    <button type="button" data-toggle="modal" data-target="#modalNuevaNomina" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">Nuevo</button>
  <?php endif; ?>
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
      
        <div class="row">
          <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "no") : ?>
            <div class="col-md-4">
              <div class="form-group">
                <label>Zona:</label>
                <select class="form-control form-control-sm" name="zona" id="zona" required>
                  <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "no") : ?>
                    <option value="all" <?php echo ($zonaId == "all") ? "selected" : "" ?>>---TODAS---</option>
                  <?php endif; ?>
                  <?php foreach ($zonas as $dataZona) : ?>
                    <option value="<?php echo $dataZona['idzona'] ?>" <?php echo ($zonaId == $dataZona['idzona']) ? "selected" : "" ?>>
                      <?php echo strtoupper($dataZona["nombre"]) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label>Mes:</label>
                <input class="form-control form-control-sm" type="month" id="mesInicial" name="mesInicial" value="<?php echo $mesInicial ?>">
              </div>
            </div>
          <?php endif; ?>

          <div class="col-md-4">
            <form action="" method="POST">
              <div class="form-group">
                <label for="valor_fondo">Valor Fondo:</label>
                <input type="number" class="form-control form-control-sm" id="valor_fondo" name="valor_fondo" required>
              </div>
              <button type="submit" class="btn btn-success btn-sm">Guardar</button>
            </form>
          </div>
        </div>

        <div class="row">
          <div class="col-md-2 pull-right">
            <input class="btn btn-primary btn-sm" onclick="seleccionarZona();" id='busqueda' value='Buscar'>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
 
<div class="row">
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Regsitro de nóminas</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-sm listaTabla">
            <thead>
              <tr>
                <th>Id</th>
                <th>Fecha inicio</th>
                <th>Fecha fin</th>
                <th>Zona</th>
                <th>Cant. Banco</th>
                <th>Cant. Efectivo</th>
                <th>Total</th>
                <th>Observaciones</th>
                <!--<th>Cant. Empleados</th>-->
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($nominas as $nomina) : ?>
                <tr>
                  <td>
                    <?php echo $nomina['idnomina']; ?>
                  </td>
                  <td>
                    <?php echo $nomina['fecha_inicio']; ?>
                  </td>
                  <td>
                    <?php echo $nomina['fecha_fin']; ?>
                  </td>
                  <td>
                    <?php echo $nomina['zona_nombre']; ?>
                  </td>
                  <td>
                    <?php echo $nomina['banco']; ?>
                  </td>
                  <td>
                    <?php echo $nomina['efectivo']; ?>
                  </td>
                  <td>
                    <?php echo $nomina['total']; ?>
                  </td>
                  <!--<td>
                    <?php echo $nomina['cant_empleados']; ?>
                  </td>-->
                  <td>
                    <?php echo $nomina['observaciones']; ?>
                  </td>
                  <td>
                    <a class="btn btn-sm btn-light" type="button" data-toggle="tooltip" title="Editar" href="index.php?action=nominas/detalles.php&id=<?php echo $nomina['idnomina']; ?>">
                      <i class="fas fa-pencil-alt"></i>
                    </a>
                    <button class="btn btn-sm btn-primary eliminar" type="button" data-toggle="tooltip" title="Eliminar" onclick="confirmarEliminarNomina(<?php echo $nomina['idnomina']; ?>)">
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
<!-- Modal Nueva Nómina -->
<div class="modal fade" id="modalNuevaNomina" tabindex="-1" role="dialog" aria-labelledby="modalNomina" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <form action="../controller/Nominas/Insertar.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="modalNomina">Nueva Nómina</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body nuevaNominaForm">
          <div class="row">
            <!-- Selección de Zona -->
            <div class="col-md-4">
              <div class="form-group">
                <label>Zona:</label>
                <select class="form-control form-control-sm" name="zona" required>
                  <option value="" selected disabled hidden>Seleccione opción</option>
                  <?php foreach ($zonas as $dataZona) : ?>
                    <option value="<?php echo $dataZona['idzona'] ?>" <?php echo ($zonaId == $dataZona['idzona']) ? "selected" : "" ?>>
                      <?php echo strtoupper($dataZona["nombre"]) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <!-- Selección de Fondo de Ahorro -->
            <div class="col-md-4">
  <div class="form-group">
    <label for="fondoSelect">Fondo de Ahorro:</label>
    <select id="fondoSelect" name="fondo" class="form-control form-control-sm" aria-label="Fondo select" required>
      <option value="" selected disabled hidden>Seleccione fondo</option>
      <?php $fondos = $modelNomina->base_datos->select("fondo", ["id", "valor_fondo"]);
      foreach ($fondos as $fondo): ?>
        <option value="<?php echo htmlspecialchars($fondo['valor_fondo'], ENT_QUOTES, 'UTF-8'); ?>">
          Fondo ID: <?php echo htmlspecialchars($fondo['id'], ENT_QUOTES, 'UTF-8'); ?> - Valor: <?php echo htmlspecialchars($fondo['valor_fondo'], ENT_QUOTES, 'UTF-8'); ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
</div>

            <!-- Fechas Desde y Hasta -->
            <div class="col-md-3">
              <div class="form-group">
                <label>Desde:</label>
                <input class="form-control form-control-sm" type="date" name="fechaInicial" value="<?php echo date("Y-m-d") ?>" required>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Hasta:</label>
                <input class="form-control form-control-sm" type="date" name="fechaFinal" value="<?php echo date("Y-m-d") ?>" required>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer con Botones -->
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
          <input type="submit" class="btn btn-primary btn-sm" value="Generar nómina">
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Nueva Nómina -->

<script>
  $("#modalNuevaNomina").modal({
    show: false,
    backdrop: 'static',
    keyboard: false
  });

  function seleccionarZona() {
    let zonaSeleccionada = $("#zona").val();
    let fechaInicial = $("#mesInicial").val();
    window.location.href = "index.php?action=nominas/index.php&zona=" + zonaSeleccionada + "&mesInicial=" + fechaInicial;
  }

  function confirmarEliminarNomina(id) {
    alertify.confirm("¿Realmente desea eliminar la nómina?",
        function() {
          $.ajax({
            type: "POST",
            url: "../controller/Nominas/Eliminar.php",
            data: {
              id: id
            },
            success: function(data) {
              location.reload();
              alertify.success("Nómina eliminada");
            },
            error: function(data) {
              alertify.success("No se pudo eliminar la nómina");
            }
          });
        },
        function() {})
      .set({
        title: "Eliminar Nómina"
      })
      .set({
        labels: {
          ok: 'Aceptar',
          cancel: 'Cancelar'
        }
      });
  }
</script>