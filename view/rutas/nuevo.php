<?php
$modelZona = new ModelZona();
$modelRuta = new ModelRuta();
$modelPermiso = new ModelPermiso();
$modelCompania = new ModelCompania();
$zonas = $modelZona->listaTodas();
$companias = $modelCompania->listaTodas();
$tiposRuta = $modelRuta->listaTiposRutaTodos();
$tiposGananciaRuta = $modelRuta->listaTiposGananciaRutaTodos();
$permisos = $modelPermiso->listaTodos();
?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="index.php?action=rutas/index.php">Rutas</a> /
    <a href="#">Nueva</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Nueva Ruta</h1>
</div>

<!-- Content Row -->
<div class="row">
  <!-- Nuevo -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Body -->
      <div class="card-body">
        <form action="../controller/Rutas/Insertar.php" method="POST" name="formNuevaRuta">
          <div class="row">
            <div class="col-md-8">
              <div class="row">
                <div class="col-md-2">Zona: </div>
                <div class="col-md-6">
                  <select class="form-control form-control-sm" id="zona" name="zona" required>
                    <option value="" selected disabled>Seleccione Opción</option>
                    <?php foreach ($zonas as $data) : ?>
                      <option value='<?php echo $data["idzona"] ?>' <?php echo ($zona == $data["idzona"]) ? "selected" : "" ?>>
                        <?php echo $data["nombre"] ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">Nombre de Ruta: </div>
                <div class="col-md-6">
                  <input class="form-control form-control-sm" type="text" value="" name="ruta" id="ruta" required>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">Tipo de Ruta: </div>
                <div class="col-md-6">
                  <select class="form-control form-control-sm" id="tipo_ruta" name="tipo_ruta" required>
                    <?php foreach ($tiposRuta as $tipo) : ?>
                      <option value="<?php echo $tipo['idtiporuta'] ?>"><?php echo $tipo["nombre"] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">Tipo de venta: </div>
                <div class="col-md-6">
                  <select class="form-control form-control-sm" id="tipo_ganancia_ruta_id" name="tipo_ganancia_ruta_id" required>
                    <?php foreach ($tiposGananciaRuta as $tipoGananciaRuta) : ?>
                      <option value="<?php echo $tipoGananciaRuta['idtipogananciaruta'] ?>"><?php echo $tipoGananciaRuta["nombre"] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">Teléfono: </div>
                <div class="col-md-6"><input class="form-control form-control-sm" type="number" value="" name="telefono" id="telefono"></div>
              </div>
              <div class="row">
                <div class="col-md-2">Capacidad: </div>
                <div class="col-md-6">
                  <input class="form-control form-control-sm" type="text" value="0" id="capacidad" name="capacidad">
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">Mínimo Venta: </div>
                <div class="col-md-6">
                  <input class="form-control form-control-sm" type="text" value="0" id="inventarioMinimo" name="inventarioMinimo">
                </div>
              </div>
              <div class="row">
                <div class="col-md-2" id="lblVendedor">Vendedor:</div>
                <div class="col-md-6">
                  <select class="form-control form-control-sm" name="vendedorSelect" id="vendedorSelect">
                    <option value="">Sin asignación</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2" id="lblAyudante">Ayudante:</div>
                <div class="col-md-6">
                  <select class="form-control form-control-sm" name="ayudanteSelect" id="ayudanteSelect">
                    <option value="">Sin asignación</option>

                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">Ciudad:</div>
                <div class="col-md-6">
                  <select class="form-control form-control-sm" id="ciudad" name="ciudad" onchange="">
                    <option value="" selected>Seleccione Opción</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-6">
                  <textarea class='form-control form-control-sm' id='txtciudad' name='txtciudad' rows='4' cols='19'></textarea>
                </div>
                <div class="col-md-2">
                  <input class='btn btn-sm btn-warning' type='button' onclick='asignarCiudad();' value='Agregar'>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">Colonia / Rancho:</div>
                <div class="col-md-6">
                  <input class="form-control form-control-sm" type="text" value="" name="colonia" id="colonia">
                </div>
              </div>
              <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-6">
                  <textarea class='form-control form-control-sm' id='txtcolonia' name='txtcolonia' rows='4' cols='19'></textarea>
                </div>
                <div class="col-md-2">
                  <button class='btn btn-sm btn-warning' type='button' onclick='asignarColonia();'>Agregar</button>
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
                          <input type="checkbox" class="form-check-input" id="exampleCheck1" name="permisos[<?php echo $permiso['idpermiso'] ?>]">
                          <label class="form-check-label" for="exampleCheck1"><?php echo $permiso['descripcion'] ?></label>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-1 offset-md-11">
              <input class="btn btn-primary btn-sm" type="submit" value="Guardar">
            </div>
          </div>
        </form>
      </div>
      <!-- Card Body -->
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('#inventarioMinimo').prop('readonly', true);
    $("#inventarioMinimo").val(0);
  });
  $("#tipo_ruta").change(function() {
    var tipoRuta = $("#tipo_ruta").val();
    $('#inventarioMinimo').prop('readonly', true);
    $("#inventarioMinimo").val(0);

    if (tipoRuta == 1 || tipoRuta == 4 || tipoRuta == 5 || tipoRuta == 7 || tipoRuta == 8 || tipoRuta == 9) {
      $("#capacidad").prop("disabled", false);
      $("#capacidad").val(0);
      if (tipoRuta == 7 || tipoRuta == 8 || tipoRuta == 9) $('#inventarioMinimo').prop('readonly', false);
    } else {
      $("#capacidad").prop("disabled", true);
      $("#capacidad").val("No Aplica");
    }
  });

  $("#zona").change(function() {
    let zonaId = $("#zona").val();

    $("#ciudad").empty().append('<option value="0" selected disabled>Seleccione Opción</option>');
    $("#vendedorSelect").empty().append('<option value="" selected disabled>Seleccione Opción</option>');
    $("#vendedorSelect").append('<option value=""  >Sin asignación</option>');
    $("#ayudanteSelect").empty().append('<option value="" selected disabled>Seleccione Opción</option>');
    $("#ayudanteSelect").append('<option value=""  >Sin asignación</option>');
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
    let tipoRuta = $('#tipo_ruta').val();

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

  function asignarCiudad() {
    textoCiudad = $("#txtciudad").val();
    console.log(textoCiudad);
    console.log($("select[name='ciudad'] option:selected").text());
    if (textoCiudad !== "") {
      $("#txtciudad").val(textoCiudad + '|' + $("select[name='ciudad'] option:selected").text());
    } else {
      $("#txtciudad").val($('select[name="ciudad"] option:selected').text());
    }
  }

  function asignarColonia() {
    textoColonia = document.getElementById("txtcolonia").value;

    if (textoColonia != "") {
      $('#txtcolonia').val(textoColonia + '|' + $('#colonia').val());
    } else {
      $('#txtcolonia').val($('#colonia').val());
    }
    document.getElementById('colonia').value = "";
  }
</script>