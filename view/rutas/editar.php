<?php
$modelZona = new ModelZona();
$modelRuta = new ModelRuta();
$modelPermiso = new ModelPermiso();
$modelCompania = new ModelCompania();
$modelClientePedido = new ModelClientePedido;
$modelEmpleado = new ModelEmpleado();
$rutaId = $_GET["id_ruta"];

$ruta = $modelRuta->obtenerRutaId($rutaId);
$tiposRuta = $modelRuta->listaTiposRutaTodos();
$tiposGananciaRuta = $modelRuta->listaTiposGananciaRutaTodos();

if (isset($_GET["zona"])) {
  $zonaData = $_GET["zona"];
}

$zona = $ruta["zona_id"];
$zonaId = $ruta["zona_id"];

$vendedores1 = $modelEmpleado->obtenerEmpleadosZonaTipoRuta($zonaId, $ruta['tipo_ruta_id'], 1); //Principal
$vendedores2 = $modelEmpleado->obtenerEmpleadosZonaTipoRuta($zonaId, $ruta['tipo_ruta_id'], 2); //Ayudante

$zonas = $modelZona->listaTodas();
$companias = $modelCompania->listaTodas();
$ciudades = null;

$permisos = $modelPermiso->listaTodos();
$permisosRuta = $modelPermiso->listaPorRuta($rutaId);
$permisosRuta = array_column($permisosRuta, 'idpermisoruta');
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="index.php?action=rutas/index.php">Rutas</a> /
    <a href="#">Editar</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Editar Ruta</h1>
</div>

<!-- Content Row -->
<div class="row">
  <!-- Nuevo -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Body -->
      <div class="card-body">
        <form action="../controller/Rutas/Actualizar.php" method="POST" name="formRuta" id="formRuta">
          <input type="hidden" id="rutaId" name="rutaId" value="<?php echo $rutaId ?>" />
          <div class="row">
            <div class="col-md-8">
              <div class="row">
                <div class="col-md-2">Nombre de Ruta: </div>
                <div class="col-md-6"><input class="form-control form-control-sm" type="text" value="<?php echo $ruta['clave_ruta'] ?>" name="ruta" id="ruta" required></div>
              </div>
              <div class="row">
                <div class="col-md-2">Tipo de Ruta: </div>
                <div class="col-md-6"><select class="form-control form-control-sm" id="tipo_ruta" name="tipo_ruta">
                    <?php foreach ($tiposRuta as $tipo) : ?>
                      <option value="<?php echo $tipo['idtiporuta'] ?>" <?php echo ($tipo['idtiporuta'] == $ruta['tipo_ruta_id']) ? "selected" : "" ?>>
                        <?php echo $tipo["nombre"] ?></option>
                    <?php endforeach; ?>
                  </select></div>
              </div>
              <div class="row">
                <div class="col-md-2">Tipo de venta: </div>
                <div class="col-md-6">
                  <select class="form-control form-control-sm" id="tipo_ganancia_ruta_id" name="tipo_ganancia_ruta_id" required>
                    <?php foreach ($tiposGananciaRuta as $tipoGananciaRuta) : ?>
                      <option value="<?php echo $tipoGananciaRuta['idtipogananciaruta'] ?>" <?php echo ($tipoGananciaRuta['idtipogananciaruta'] == $ruta['tipo_ganancia_ruta_id']) ? "selected" : "" ?>><?php echo $tipoGananciaRuta["nombre"] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">Teléfono: </div>
                <div class="col-md-6"><input class="form-control form-control-sm" type="number" value="<?php echo $ruta['telefono'] ?>" name="telefono" id="telefono"></div>
              </div>
              <div class="row">
                <div class="col-md-2">Capacidad: </div>
                <div class="col-md-6"><input class="form-control form-control-sm" type="text" id="capacidad" name="capacidad" value="<?php echo $ruta['capacidad'] ?>"></div>
              </div>
              <div class="row">
                <div class="col-md-2">Mínimo Venta: </div>
                <div class="col-md-6">
                  <input class="form-control form-control-sm" type="text" id="inventarioMinimo" name="inventarioMinimo" value="<?php echo $ruta['inventario_minimo'] ?>">
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">Zona: </div>
                <div class="col-md-6"><select class="form-control form-control-sm" id="zona" name="zona" required>
                    <?php foreach ($zonas as $data) : ?>
                      <option data-zona-id="<?php echo $data['idzona'] ?>" value="<?php echo $data['idzona'] ?>" <?php echo (strcmp($zona, $data["idzona"]) == 0) ? "selected" : ""; ?>>
                        <?php echo $data["nombre"] ?>
                      </option>
                    <?php endforeach; ?>
                  </select></div>
              </div>
              <div class="row">
                <div class="col-md-2" id="lblVendedor">Vendedor:
                </div>
                <div class="col-md-6">
                  <select class="form-control form-control-sm" name="vendedorSelect" id="vendedorSelect">
                    <option value="" selected disabled>Seleccione Opción</option>
                    <option value="">Sin asignación</option>
                    <?php
                    foreach ($vendedores1 as $vendedor) : ?>
                      <option value="<?= $vendedor->idempleado ?>" <?php echo ($vendedor->idempleado == $ruta["vendedor1"]) ? "selected" : ""; ?>>
                        <?= $vendedor->nombre ?>
                      </option>
                    <?php
                    endforeach;
                    ?>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2" id="lblAyudante">Ayudante: </div>
                <div class="col-md-6">
                  <select class="form-control form-control-sm" name="ayudanteSelect" id="ayudanteSelect">
                    <option value="" selected disabled>Seleccione Opción</option>
                    <option value="">Sin asignación</option>
                    <?php
                    foreach ($vendedores2 as $vendedor2) : ?>
                      <option value="<?= $vendedor2->idempleado ?>" <?php echo ($vendedor2->idempleado == $ruta["vendedor2"]) ? "selected" : ""; ?>>
                        <?= $vendedor2->nombre ?>
                      </option>
                    <?php
                    endforeach;
                    ?>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">Ciudad: </div>
                <div class="col-md-6"><select class="form-control form-control-sm" id="ciudad" name="ciudad">
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-6"><textarea class='form-control form-control-sm' id='listaCiudades' name='listaCiudades' rows='4' cols='19'><?php echo $ruta['ciudades'] ?></textarea></div>
                <div class="col-md-2">
                  <button class="btn btn-sm btn-warning" type='button' onclick='agregarCiudad();'>Agregar</button>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">Colonia / Rancho: </div>
                <div class="col-md-6"><input class="form-control form-control-sm" type="text" value="" name="colonia" id="colonia"></div>
              </div>
              <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-6"><textarea class='form-control form-control-sm' id='listaColonias' name='listaColonias' rows='4' cols='19'><?php echo $ruta["calles"] ?></textarea></div>
                <div class="col-md-2">
                  <button class="btn btn-sm btn-warning" type='button' onclick='agregarColonia();'>Agregar</button>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card border-light mb-3" style="max-width: 18rem;">
                <div class="card-header"><i class="fas fa-key fa-sm text-secondary"></i> Permisos</div>
                <div class="card-body">
                  <?php foreach ($permisos as $permiso) : ?>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-check">
                          <input type="checkbox" class="form-check-input" id="exampleCheck1" name="permisos[<?php echo $permiso['idpermiso'] ?>]" <?php echo (in_array($permiso['idpermiso'], $permisosRuta)) ? "checked" : "" ?>>
                          <label class="form-check-label" for="exampleCheck1"><?php echo $permiso['descripcion'] ?></label>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>
        </form>
        <div class="row">
          <div class="col-md-2 offset-11">
            <div class="form-group">
              <input class="btn btn-primary btn-sm" type="submit" value="Guardar" form="formRuta">
            </div>
          </div>
        </div>
      </div>
      <!-- Card Body -->
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    activarCapacidad();
    obtenerCiudades();

    let tipoRuta = $('#tipo_ruta').val();

    //Escondemos el ayudante en caso que sea una estación de carburación y debemos mostrar el select de empleados de esa estación.
    if (tipoRuta == 5) {
      $('#lblAyudante').text("Vendedor:");
    } else {
      $('#lblAyudante').text("Ayudante:");
    }
  });

  $("#tipo_ruta").change(function() {
    activarCapacidad();
  });

  function activarCapacidad() {
    $('#inventarioMinimo').prop('readonly', true);
    let tipo_ruta = $("#tipo_ruta").val();

    if (tipo_ruta == 1 || tipo_ruta == 4 || tipo_ruta == 5 || tipo_ruta == 7 || tipo_ruta == 8 || tipo_ruta == 9) {
      $("#capacidad").prop("disabled", false);
      if (tipo_ruta == 7 || tipo_ruta == 8 || tipo_ruta == 9) {
        $('#inventarioMinimo').prop('readonly', false);
      }
    } else {
      $("#capacidad").prop("disabled", true);
      $("#capacidad").val("No Aplica");
    }
  }

  var x = 0 // variable declarada e iniciada como cero

  function agregarCiudad() {
    x = 0 // variable declarad e iniciada como cero
    if (x == 0) {
      //solo entra aquí cuando x sea cero
      texto = document.getElementById("listaCiudades").value;

      if (texto != "") {
        document.getElementById("listaCiudades").value = texto + '|' + document.getElementById('ciudad').options[document.getElementById('ciudad').selectedIndex].text;
      } else {
        document.getElementById("listaCiudades").value = document.getElementById('ciudad').options[document.getElementById('ciudad').selectedIndex].text;
      }

      x++; // variable cambia a uno despues de copiar el texto.
    }
  }

  var x = 0 // variable declarada e iniciada como cero

  function agregarColonia() {
    x = 0 // variable declarad e iniciada como cero
    if (x == 0) {
      //solo entra aquí cuando x sea cero
      txtColonias = $("#listaColonias").val();

      if (txtColonias != "") {
        $("#listaColonias").val(txtColonias + '|' + $('#colonia').val());
      } else {
        $("#listaColonias").val($('#colonia').val());
      }
      $('#colonia').val("");
      x++; // variable cambia a uno despues de copiar el texto.
    }
  }

  function obtenerCiudades() {
    let zonaId = $('#zona option:selected').attr('data-zona-id');

    $("#ciudad").empty();
    $.ajax({
      data: {
        zonaId: zonaId
      },
      type: "GET",
      url: '../controller/Rutas/CargarCiudadesRuta.php',
      dataType: "json",
      success: function(data) {
        $.each(data, function(key, ciudad) {
          $("#ciudad").append('<option value=' + ciudad.id + '>' + ciudad.ciudad + '</option>');
        });
      },
      error: function(data) {
        alertify.error('Ha ocurrido un error al cargar las ciudades');
      }
    });
  }

  $("#zona").change(function() {
    let zonaId = $("#zona").val();
    let tipoRuta = $('#tipo_ruta').val();

    $("#ciudad").empty().append('<option value="0" selected disabled>Seleccione Opción</option>');
    $("#vendedorSelect").empty().append('<option value="" selected disabled>Seleccione Opción</option>');
    $("#vendedorSelect").append('<option value="" selected disabled>Sin asignación</option>');
    $("#ayudanteSelect").empty().append('<option value="" selected disabled>Seleccione Opción</option>');
    $("#ayudanteSelect").append('<option value="" selected disabled>Sin asignación</option>');
    $.ajax({
      data: {
        zonaId: zonaId
      },
      type: "GET",
      url: '../controller/Rutas/CargarCiudadesRuta.php',
      dataType: "json",
      success: function(data) {
        $.each(data, function(key, ciudad) {
          $("#ciudad").append('<option value=' + ciudad.idciudad + '>' + ciudad.ciudad + '</option>');
        });
      },
      error: function(data) {
        alertify.error('Ha ocurrido un error al cargar las ciudades');
      }
    });

    obtenerEmpleadosTipoRuta(zonaId, tipoRuta);

  });

  $("#tipo_ruta").change(function() {
    let zonaId = $("#zona").val();
    let tipoRuta = $('#tipo_ruta').val();
    // $("#ciudad").empty().append('<option value="0" selected disabled>Seleccione Opción</option>');
    $("#vendedorSelect").empty().append('<option value="" selected disabled>Seleccione Opción</option>');
    $("#vendedorSelect").append('<option value=""  >Sin asignación</option>');
    $("#ayudanteSelect").empty().append('<option value="" selected disabled>Seleccione Opción</option>');
    $("#ayudanteSelect").append('<option value=""  >Sin asignación</option>');

    //Escondemos el ayudante en caso que sea una estación de carburación y debemos mostrar el select de empleados de esa estación.
    if (tipoRuta == 5) {
      $('#lblAyudante').text("Vendedor:");
    } else {
      $('#lblAyudante').text("Ayudante:");
    }

    obtenerEmpleadosTipoRuta(zonaId, tipoRuta);
  });

  function obtenerEmpleadosTipoRuta(zonaId, tipoRuta) {
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
          $("#vendedorSelect").append('<option value=' + empleado.idempleado + '>' + empleado.nombre + '</option>');
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
          $("#ayudanteSelect").append('<option value=' + empleado.idempleado + '>' + empleado.nombre + '</option>');
        });
      },
      error: function(data) {
        alertify.error('Ha ocurrido un error al cargar los empleados');
        console.log(data);
      }
    });
  }
</script>