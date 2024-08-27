<?php
$modelZona = new ModelZona();
$modelRuta = new ModelRuta();

//Validar zonas disponibles
if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "no") {
  $zonaId = (!empty($_GET["zona"])) ? $_GET["zona"] : "all";
  $zonas = $modelZona->obtenerTodas();
} elseif ($_SESSION["tipoUsuario"] == "mv") { //Es un usuario multizona de captura de ventas
  $zonas = $modelZona->obtenerZonasPorUsuario($_SESSION["id"]);
  $zonaId = (!empty($_GET["zona"])) ? $_GET["zona"] : $zonas[0]["idzona"];
} else {
  $zonaId = $_SESSION['zonaId'];
}

//Obtener rutas
if ($zonaId == "all") {
  $rutas = $modelRuta->listaTodasEstatus(1);
} else {
  $rutas = $modelRuta->listaPorZonaEstatus($zonaId, 1);
}
?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Rutas</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Rutas</h1>
  <?php if ($_SESSION['tipoUsuario'] == "su" || $_SESSION["tipoUsuario"] == "no") : ?>
    <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" href="index.php?action=rutas/nuevo.php">Nueva</a>
  <?php endif; ?>
</div>
<?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "mv" || $_SESSION["tipoUsuario"] == "no") : ?>
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
              </div>
              <div class="row">
                <div class="col-md-2 pull-right">
                  <br>
                  <input type='hidden' name='action' id='action' value="rutas/index.php" />
                  <input class="btn btn-primary btn-sm" type='submit' id='busqueda' value='Buscar'>
                </div>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>
<!-- Content Row -->
<div class="row">
  <!-- Nuevo Pedido -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Header -->
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Lista Rutas</h6>
      </div>
      <!-- Card Header -->
      <!-- Card Body -->
      <div class="card-body">
        <?php if ($_SESSION["tipoUsuario"] == "su") : ?>
          <div class="row">
            <div class="alert alert-warning col-md-12" role="alert">
              Las rutas con el icono <i class="fas fa-key fa-sm"></i> tienen permisos especiales asignados.
            </div>
          </div>
        <?php endif; ?>
        <div class="row">
          <table id="listaRutas" class="table table-responsive table-bordered table-sm">
            <thead>
              <tr>
                <th>Zona</th>
                <th>Compañía</th>
                <th>Ruta</th>
                <th>Tipo Ruta</th>
                <th>Capacidad</th>
                <th>Inventario Mínimo</th>
                <th>Vendedores</th>
                <th>Teléfono</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($rutas as $ruta) : ?>
                <tr class="text-center">
                  <td><?php echo $ruta["zona_nombre"] ?></td>
                  <td><?php echo $ruta["compania_nombre"] ?></td>
                  <td><?php if (($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc") && $ruta["cantidad_permisos"] > 0) : ?>
                      <i class="fas fa-key fa-sm text-secondary"></i>
                    <?php endif; ?>
                    <?php echo $ruta["clave_ruta"] ?>
                  </td>
                  <td><?php echo $ruta["tipo_ruta_nombre"] . (($_SESSION["tipoUsuario"] != "u") ? "<br>(" . $ruta["tipo_ganancia_ruta_nombre"] . ")" : "") ?></td>
                  <td><?php echo $ruta["capacidad"] ?></td>
                  <td><?php echo $ruta["inventario_minimo"] ?></td>
                  <td><?php echo $ruta["vendedor1_nombre"] . " - " . $ruta["vendedor2_nombre"] ?></td>
                  <td><?php echo $ruta["telefono"] ?></td>
                  <td>
                    <?php if ($_SESSION["tipoUsuario"] == "no") : ?>
                      <form action='index.php' method='GET'>
                        <input type='hidden' name='action' value='rutas/editar.php'>
                        <button type='submit' class='btn btn-sm btn-light'><i class='fas fa-pencil-alt'></i></button>
                        <input type='hidden' name='id_ruta' value='<?php echo $ruta["idruta"] ?>'>
                        <input type='hidden' name='zona' value='<?php echo $ruta["zona_id"] ?>'>
                        <input type='hidden' name='name' value='&&'>
                      </form>
                    <?php endif; ?>
                    <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc") : ?>
                      <form action='index.php' method='GET'>
                        <input type='hidden' name='action' value='rutas/editar.php'>
                        <button type='submit' class='btn btn-sm btn-light'><i class='fas fa-pencil-alt'></i></button>
                        <input type='hidden' name='id_ruta' value='<?php echo $ruta["idruta"] ?>'>
                        <input type='hidden' name='zona' value='<?php echo $ruta["zona_id"] ?>'>
                        <input type='hidden' name='name' value='&&'>
                      </form>
                      <!-- Estatus Baja(0) Activo(1)-->
                      <button class="btn btn-sm btn-primary" type='button' <?php echo ($ruta["estatus"] == 0) ? "style='display:none'" : "" ?> id="desactivar<?php echo $ruta["idruta"] ?>" onclick="eliminar('<?php echo $ruta['idruta']; ?>');" data-toggle="tooltip" title="Eliminar"><i class='fas fa-trash'></i></button>
                      <button class="btn btn-sm btn-warning" type='button' <?php echo ($ruta["estatus"] == 1) ? "style='display:none'" : "" ?> id="activar<?php echo $ruta["idruta"] ?>" onclick="activar('<?php echo $ruta['idruta']; ?>');" data-toggle="tooltip" title="Activar"><i class='fas fa-trash-restore fa-sm'></i></button>
                    <?php elseif ($_SESSION["tipoUsuario"] == "u") : ?>
                      <button class='btn btn-sm btn-light' type='button' onclick="abrirModalEditarUsuarioZona('<?php echo $ruta['idruta'] ?>','<?php echo $ruta['tipo_ruta_id'] ?>','<?php echo $ruta['vendedor1'] ?>','<?php echo $ruta['vendedor2'] ?>','<?php echo $ruta['clave_ruta'] ?>','<?php echo $ruta['telefono'] ?>')" ;'><i class='fas fa-pencil-alt'></i></button>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
      <!-- Card Body -->
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalEditarUsuarioZona" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="../controller/Rutas/ActualizarTelefonoVendedores.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Editar datos</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-3">
              Ruta
            </div>
            <div class="col-md-9">
              <label id="rutaNombre"></label>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              Teléfono
            </div>
            <div class="col-md-9">
              <input type="number" class="form-control form-control-sm" name="rutaTelefono" id="rutaTelefono">
            </div>
          </div>
          <div class="row">
            <div class="col-md-3" id="lblVendedor">Vendedor:
            </div>
            <div class="col-md-9">
              <select class="form-control form-control-sm" name="vendedorSelect" id="vendedorSelect">
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3" id="lblAyudante">Ayudante: </div>
            <div class="col-md-9">
              <select class="form-control form-control-sm" name="ayudanteSelect" id="ayudanteSelect">
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="rutaId" id="rutaId" required>
          <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $("#zona").select2({});
  });

  $('#listaRutas').DataTable({
    "pageLength": 25,
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
    }
  });

  $("#modalEditarUsuarioZona").modal({
    show: false,
    backdrop: 'static',
    keyboard: false
  });

  //El método desactiva la ruta pero la oculta al usuario
  function eliminar(rutaId) {
    alertify.confirm("¿Realmente desea eliminar la ruta seleccionada?",
        function() {
          $.ajax({
            type: "POST",
            url: "../controller/Rutas/Desactivar.php",
            data: {
              id: rutaId
            },
            success: function(data) {
              location.reload();
              alertify.success("Ruta eliminada exitosamente");
            }
          });
        },
        function() {})
      .set({
        title: "Eliminar ruta"
      })
      .set({
        labels: {
          ok: 'Aceptar',
          cancel: 'Cancelar'
        }
      });
  }

  function activar(id) {
    var id = id;
    $.ajax({
      type: "POST",
      url: "../../controller/Rutas/Activar.php",
      data: {
        id: id
      },
      success: function(data) {
        $("#desactivar" + id).show();
        $("#activar" + id).hide();
        alertify.success("Activación de ruta exitosa");
      }
    });
  }

  //Desactiva la ruta pero la sigue mostrando, sólo cambia el ícono
  function desactivar(id) {
    var id = id;
    $.ajax({
      type: "POST",
      url: "../../controller/Rutas/Desactivar.php",
      data: {
        id: id
      },
      success: function(data) {
        $("#activar" + id).show();
        $("#desactivar" + id).hide();
        alertify.message("Desactivación de ruta exitosa");
      }
    });
  }

  function abrirModalEditarUsuarioZona(rutaId, tipoRuta,vendedor1,vendedor2, rutaNombre, telefono) {
    let zonaId = "<?php echo $zonaId ?>";
    $("#rutaId").val(rutaId);
    $("#rutaNombre").text(rutaNombre);
    $("#rutaTelefono").val(telefono);
    
    $("#vendedorSelect").empty().append('<option value="" selected >Sin asignación</option>');
    $("#ayudanteSelect").empty().append('<option value="" selected>Sin asignación</option>');

    //Escondemos el ayudante en caso que sea una estación de carburación y debemos mostrar el select de empleados de esa estación.
    if (tipoRuta == 5) {
      $('#lblAyudante').text("Vendedor:");
    } else {
      $('#lblAyudante').text("Ayudante:");
    }

    obtenerEmpleadosTipoRuta(zonaId, tipoRuta,vendedor1,vendedor2);

    $("#modalEditarUsuarioZona").modal("show");
  }

  function obtenerEmpleadosTipoRuta(zonaId, tipoRuta,vendedor1,vendedor2) {
    //Cargar vendedores principales
    $.ajax({
      data: {
        zonaId: zonaId,
        tipoRuta: tipoRuta,
        tipoVendedorRuta: 1 //Principal
      },
      type: "GET",
      url: '../controller/Empleados/ObtenerEmpleadosZonaTipoRuta.php',
      dataType: "json",
      success: function(data) {
        $.each(data, function(key, empleado) {
          let selected = (empleado.idempleado == vendedor1) ? "selected":"";
          $("#vendedorSelect").append('<option '+selected+' value=' + empleado.idempleado + '>' + empleado.nombre + '</option>');
        });
      },
      error: function(data) {
        alertify.error('Ha ocurrido un error al cargar los empleados');
      }
    });
    //Cargar vendedores ayudantes
    $.ajax({
      data: {
        zonaId: zonaId,
        tipoRuta: tipoRuta,
        tipoVendedorRuta: 2 //Ayudante
      },
      type: "GET",
      url: '../controller/Empleados/ObtenerEmpleadosZonaTipoRuta.php',
      dataType: "json",
      success: function(data) {

        $.each(data, function(key, empleado) {
          let selected = (empleado.idempleado == vendedor2) ? "selected":"";
          $("#ayudanteSelect").append('<option '+selected+' value=' + empleado.idempleado + '>' + empleado.nombre + '</option>');
        });
      },
      error: function(data) {
        alertify.error('Ha ocurrido un error al cargar los empleados');
        console.log(data);
      }
    });
  }
</script>