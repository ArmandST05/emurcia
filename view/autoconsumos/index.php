<?php
$modelZona = new ModelZona();
$modelCompania = new ModelCompania();
$modelAutoconsumo = new ModelAutoconsumo();
//Búsqueda de datos
$fechaInicial = (!empty($_GET['fechaInicial'])) ? $_GET['fechaInicial'] : date("Y-m-d");
$fechaFinal = (!empty($_GET['fechaFinal'])) ? $_GET['fechaFinal'] : date("Y-m-d");
$rutaId = (!empty($_GET['ruta'])) ? $_GET['ruta'] : 0;
$productoNombre = (!empty($_GET['producto'])) ? $_GET['producto'] : "0";

if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "inv") {
  $zonas = $modelZona->obtenerZonasGas();
  $companias = $modelCompania->listaPorEstatus(1);
  $zonaId = (!empty($_GET['zona'])) ? $_GET['zona'] : 0;
  $companiaId = (!empty($_GET['compania'])) ? $_GET['compania'] : 0;
} else {
  $companiaId = 0;
  $zonaId = $_SESSION['zonaId'];
}

if ($companiaId != 0) {
  //Búsqueda por compañía - Obtener autoconsumos de todas las zonas de la compañía
  if($productoNombre != "0") $autoconsumos = $modelAutoconsumo->obtenerAutoconsumosCompaniaProductoFecha($companiaId,$productoNombre, $fechaInicial, $fechaFinal);
  else $autoconsumos = $modelAutoconsumo->obtenerAutoconsumosCompaniaFecha($companiaId, $fechaInicial, $fechaFinal);
} else if ($rutaId != 0) {
  //Búsqueda por ruta 
  if($productoNombre != "0") $autoconsumos = $modelAutoconsumo->obtenerAutoconsumosRutaProductoFecha($rutaId,$productoNombre,$fechaInicial, $fechaFinal);
  else $autoconsumos = $modelAutoconsumo->obtenerAutoconsumosRutaFecha($rutaId, $fechaInicial, $fechaFinal);
} else {
  //Búsqueda por zona (No se especificó ruta) - Obtener autoconsumos de todas las rutas de la zona
  if($productoNombre != "0") $autoconsumos = $modelAutoconsumo->obtenerAutoconsumosZonaProductoFecha($zonaId,$productoNombre, $fechaInicial, $fechaFinal);
  else $autoconsumos = $modelAutoconsumo->obtenerAutoconsumosZonaFecha($zonaId, $fechaInicial, $fechaFinal);
}

//ORDENAR DATOS
$datosAutoconsumo = [];
foreach ($autoconsumos as $autoconsumo) {
  $datosAutoconsumo[$autoconsumo['compania_id']][$autoconsumo['zona_id']][] = $autoconsumo;
}
//ORDENAR DATOS
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Autoconsumos</a>
  </div>
</div>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Autoconsumos</h1>
  <?php if($_SESSION["tipoUsuario"] == "u"):?>
  <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" href="index.php?action=autoconsumos/nuevo.php">Nuevo</a>
  <?php endif;?>
</div>

<!-- Content Row -->
<div class="row">
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Header - Dropdown -->
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Buscar</h6>
      </div>
      <!-- Card Body -->
      <div class="card-body" name="buscar" id="buscar">
        <form action='index.php' method='GET'>
          <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "inv") : ?>
            <div class="row">
              <div class="col-lg-1 col-sm-6">
                <div class="form-group">
                  <label>Compañía:</label>
                </div>
              </div>
              <div class="col-lg-4 col-sm-6">
                <div class="form-group">
                  <select class="form-control form-control-sm" name="compania" id="compania">
                    <option value="0" selected>Seleciona opción</option>
                    <?php foreach ($companias as $compania) : ?>
                      <option value="<?php echo $compania['idcompania'] ?>" <?php echo ($companiaId == $compania['idcompania']) ? "selected" : "" ?>>
                        <?php echo $compania["nombre"] ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-1 col-sm-6">
                <div class="form-group">
                  <label>Zona:</label>
                </div>
              </div>
              <div class="col-lg-4 col-sm-6">
                <div class="form-group">
                  <select class="form-control form-control-sm" name="zona" id="zona">
                    <option selected value="0">Seleciona opción</option>
                    <?php foreach ($zonas as $dataZona) : ?>
                      <option value="<?php echo $dataZona['idzona'] ?>" <?php echo ($zonaId == $dataZona['idzona']) ? "selected" : "" ?>>
                        <?php echo strtoupper($dataZona["nombre"]) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>
          <?php endif; ?>
          <div class="row">
            <div class="col-lg-1 col-sm-6">
              <div class="form-group">
                <label>Ruta:</label>
              </div>
            </div>
            <div class="col-lg-4 col-sm-6">
              <div class="form-group">
                <select class="form-control form-control-sm" name="ruta" id="ruta">
                  <option value="0">Todas</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-1 col-sm-6">
              <div class="form-group">
                <label>Combustible:</label>
              </div>
            </div>
            <div class="col-lg-4 col-sm-6">
              <div class="form-group">
                <select class="form-control form-control-sm" name="producto" id="producto">
                  <option value="0" <?php echo ($productoNombre === "0") ? "selected":""?>>Todos</option>
                  <option value="Gas LP" <?php echo ($productoNombre == "Gas LP") ? "selected":""?>>Gas LP</option>
                  <option value="Gasolina" <?php echo ($productoNombre == "Gasolina") ? "selected":""?>>Gasolina</option>
                  <option value="Diesel" <?php echo ($productoNombre == "Diesel") ? "selected":""?>>Diesel</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-1 col-sm-6">
              <div class="form-group">
                <label>Desde:</label>
              </div>
            </div>
            <div class="col-lg-4 col-sm-6">
              <input class="form-control form-control-sm" type="date" name="fechaInicial" value="<?php echo $fechaInicial ?>" required>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-1 col-sm-6">
              <div class="form-group">
                <label>Hasta:</label>
              </div>
            </div>
            <div class="col-lg-4 col-sm-6">
              <input class="form-control form-control-sm" type="date" name="fechaFinal" value="<?php echo $fechaFinal ?>" required>
            </div>
          </div>
          <input type='hidden' name='action' id='action' value="autoconsumos/index.php" />
          <div class="row">
            <div clas="col-md-1 offset-md-10">
              <input class="btn btn-primary btn-sm" type='submit' id='busqueda' value='Buscar'>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Content Row -->
<div class="row">
  <!-- Card -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Header -->
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Lista de Autoconsumos</h6>
      </div>
      <!-- Card Body -->
      <div class="card-body">
        <div class="row">
          <div class="col-md-3 offset-md-9">
            <button class="btn btn-warning btn-sm offset-md-4" id="btnExport"><i class="far fa-file-excel"></i> Exportar a Excel</button>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <table class="table table-bordered table-sm table-responsive" id="listaTabla" name="listaTabla">
              <thead>
                <tr>
                  <th>Ruta</th>
                  <th>Fecha inicial</th>
                  <th>Fecha final</th>
                  <th>Combustible</th>
                  <th>Litros</th>
                  <th>Costo</th>
                  <th>Total</th>
                  <th>Km inicial</th>
                  <th>Km final</th>
                  <th>Rendimiento</th>
                  <th>Acción</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($datosAutoconsumo as $claveCompania => $companiaDatos) :
                  //Recorrer la compañía
                  $totalCompaniaLitros = 0;
                  $totalCompaniaCosto = 0;
                  $companiaNombre = reset($companiaDatos);

                  $cantidadZonaAutoconsumos=0;
                  $cantidadCompaniaAutoconsumos=0;
                  $totalZonaRendimiento=0;
                  $totalCompaniaRendimiento=0;
                ?>
                  <tr class="bg-light">
                    <td colspan="11"><b><?php echo $companiaNombre[0]['compania_nombre'] ?>
                      </b></td>
                  </tr>
                  <?php foreach ($companiaDatos as $claveZona => $zonaDatos) :
                    set_time_limit(0);
                    //Zonas de la compañía
                    $totalZonaLitros = 0;
                    $totalZonaCosto = 0;
                    $zonaNombre = reset($zonaDatos);
                  ?>
                    <tr data-tt-id="<?php echo $claveZona; ?>" class="bg-light">
                      <td colspan="11"><b>
                          <?php echo $zonaNombre['zona_nombre'] ?>
                        </b></td>
                    </tr>
                    <?php
                    foreach ($zonaDatos as $claveAutoconsumo => $autoconsumoDatos) :
                      $totalZonaLitros += $autoconsumoDatos["litros"];
                      $totalZonaCosto += $autoconsumoDatos["total"];
                      $cantidadZonaAutoconsumos++;
                      $cantidadCompaniaAutoconsumos++;

                      $totalZonaRendimiento += $autoconsumoDatos["rendimiento"];
                      $totalCompaniaRendimiento += $autoconsumoDatos["rendimiento"];
                    ?>
                      <tr data-tt-parent-id="<?php echo $claveZona; ?>">
                        <td><?php echo $autoconsumoDatos["ruta_nombre"]; ?></td>
                        <td><?php echo $autoconsumoDatos["fechai"]; ?></td>
                        <td><?php echo $autoconsumoDatos["fechaf"]; ?></td>
                        <td><?php echo $autoconsumoDatos["combustible"]; ?></td>
                        <td class="text-right"><?php echo $autoconsumoDatos["litros"]; ?></td>
                        <td>$<?php echo $autoconsumoDatos["costo"] ?></td>
                        <td class="text-right">$<?php echo number_format(($autoconsumoDatos["total"]), 2); ?></td>
                        <td><?php echo $autoconsumoDatos["km_ini"]; ?></td>
                        <td><?php echo $autoconsumoDatos["km_fin"]; ?></td>
                        <td><?php echo $autoconsumoDatos["rendimiento"]; ?></td>
                        <td>
                          <?php if($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "u"): ?>
                          <button class='btn btn-sm btn-primary' type='button' onclick="eliminar('<?php echo $autoconsumoDatos['idautoconsumo']; ?>');"><i class='fas fa-trash fa-sm'></i></button>
                          <?php endif; ?>
                        </td>
                      </tr>
                    <?php endforeach;
                    //SUMAR TOTALES ZONA 
                    $totalCompaniaLitros += $totalZonaLitros;
                    $totalCompaniaCosto += $totalZonaCosto;
                    ?>
                    <tr class="bg-light">
                      <td><b>Total</b></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td class="text-right"><b><?php echo number_format(($totalZonaLitros), 2) ?></b></td>
                      <td></td>
                      <td class="text-right"><b>$<?php echo number_format(($totalZonaCosto), 2) ?></b></td>
                      <td></td>
                      <td></td>
                      <td><b><?php echo number_format(($totalZonaRendimiento/$cantidadZonaAutoconsumos), 2) ?></b></td>
                      <td></td>
                    </tr>
                  <?php endforeach ?>
                  <tr class="bg-light">
                    <td><b>TOTAL</b></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right"><b><?php echo number_format(($totalCompaniaLitros), 2) ?></b></td>
                    <td></td>
                    <td class="text-right"><b>$<?php echo number_format(($totalCompaniaCosto), 2) ?></b></td>
                    <td></td>
                    <td></td>
                    <td><b><?php echo number_format(($totalCompaniaRendimiento/$cantidadCompaniaAutoconsumos), 2) ?></b></td>
                    <td></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    obtenerRutas();
    $("#listaTabla").treetable({
      expandable: true
    });
    $('#listaTabla').treetable('collapseAll');
  });
  /*
    $('#listaTabla').DataTable({
      "pageLength": 50,
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
      }
    });*/

  $("#btnExport").click(function(e) {
    $('#listaTabla').treetable('expandAll');
    $("#listaTabla").btechco_excelexport({
      containerid: "listaTabla",
      datatype: $datatype.Table,
      filename: 'reporte-autoconsumos'
    });
  });

  $("#buscar").submit(function(event) {
    let tipoUsuario = "<?php echo $_SESSION['tipoUsuario'] ?>";
    if ((tipoUsuario == "su" || tipoUsuario == "uc" || tipoUsuario == "inv") && $("#compania").val() == 0 && $("#zona").val() == 0) {
      event.preventDefault();
      alertify.error("Elige una compañía o zona");
    }
  });

  $("#compania").change(function() {
    if ($("#compania").val() != 0) {
      $("#zona").val(0);
      obtenerRutas();
    }
  });

  $("#zona").change(function() {
    $("#compania").val(0).change();
    obtenerRutas();
  });

  function obtenerRutas() {
    let tipoUsuario = "<?php echo $_SESSION["tipoUsuario"] ?>";

    let zonaId = 0;
    if (tipoUsuario == "su" || tipoUsuario == "uc" || tipoUsuario == "inv") zonaId = $("#zona").val();
    else zonaId = "<?php echo $_SESSION["zonaId"] ?>";

    $("#ruta").empty().append('<option value="0">Todas</option>');

    $.ajax({
      data: {
        zonaId: zonaId
      },
      type: "GET",
      url: '../controller/Rutas/ObtenerPorZonaVenta.php',
      dataType: "json",
      success: function(data) {
        $.each(data, function(key, ruta) {
          let estatus_ruta = "";
          if (ruta.estatus == 0) estatus_ruta = "(INACTIVO)";
          $("#ruta").append('<option value=' + ruta.idruta + '>' + ruta.clave_ruta + ' ' + estatus_ruta + '</option>');
        });
        let rutaId = "<?php echo $rutaId ?>";
        if (rutaId) $("#ruta").val(rutaId).change();
      },
      error: function(data) {
        alertify.error('Ha ocurrido un error al cargar las rutas de la zona.');
      }
    });
  }

  function eliminar(id) {
    alertify.confirm("¿Realmente desea eliminar el autoconsumo?",
        function() {
          $.ajax({
            type: "POST",
            url: "../controller/Autoconsumos/EliminarAutoconsumo.php",
            data: {
              id: id
            },
            success: function(data) {
              location.reload();
              alertify.success("Autoconsumo eliminado");
            }
          });
        },
        function() {})
      .set({
        title: "Eliminar Autoconsumo"
      })
      .set({
        labels: {
          ok: 'Aceptar',
          cancel: 'Cancelar'
        }
      });
  }
</script>