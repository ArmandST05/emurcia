<?php
$modelZona = new ModelZona();
$modelGasto = new ModelGasto();
//Búsqueda de datos
$anio = date("Y");
$mesInicial = (!empty($_GET["mesInicial"])) ? $_GET["mesInicial"] : date("n");
$anioInicial = (!empty($_GET["anioInicial"])) ? $_GET["anioInicial"] : date("Y");

$mesFinal = (!empty($_GET["mesFinal"])) ? $_GET["mesFinal"] : date("n");
$anioFinal = (!empty($_GET["anioFinal"])) ? $_GET["anioFinal"] : date("Y");
$rutaId = (!empty($_GET["ruta"])) ? $_GET["ruta"] : 0;

if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "ga" || $_SESSION["tipoUsuario"] == "inv") {
  $zonaId = (!empty($_GET["zona"])) ? $_GET["zona"] : "";
  $zonas = $modelZona->obtenerZonasTodas();
} else {
  $zonaId = $_SESSION['zonaId'];
}

$meses = ["1" => "Enero", "2" => "Febrero", "3" => "Marzo", "4" => "Abril", "5" => "Mayo", "6" => "Junio", "7" => "Julio", "8" => "Agosto", "9" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];

if ($rutaId == 0) $gastos = $modelGasto->obtenerGastosRutasZonaEntreFechas($zonaId, $mesInicial, $anioInicial, $mesFinal, $anioFinal);
else $gastos = $modelGasto->obtenerGastosRutasZonaRutaEntreFechas($zonaId, $rutaId, $mesInicial, $anioInicial, $mesFinal, $anioFinal);

$totalGastos = 0;
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Gastos Punto Venta</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Gastos Punto Venta</h1>
  <?php if ($_SESSION["tipoUsuario"] == "u" || $_SESSION["tipoUsuario"] == "ga") : ?>
    <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" href="index.php?action=gastosruta/nuevo.php">Nuevo</a>
  <?php endif; ?>
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
      <div class="card-body">
        <form action='index.php' method='GET'>
          <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "ga" || $_SESSION["tipoUsuario"] == "inv") : ?>
            <div class="row">
              <div class="col-md-1">
                <div class="form-group">
                  <label>Zona:</label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <select class="form-control form-control-sm" name="zona">
                    <option selected disabled>Seleciona opción</option>
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
            <div class="col-md-1">
              <div class="form-group">
                <label>Ruta:</label>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <select class="form-control form-control-sm" name="ruta" id="ruta">
                  <option value="0">Todas</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-1">
              <div class="form-group">
                <label>Desde:</label>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <select class="form-control form-control-sm" name="mesInicial" id="mesInicial" required>
                  <?php for ($mi = 1; $mi <= 12; $mi++) : ?>
                    <option value="<?php echo $mi; ?>" <?php echo ($mesInicial == $mi) ? "selected" : ""; ?>>
                      <?php echo $meses[$mi]; ?></option>
                  <?php endfor; ?>
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <select class="form-control form-control-sm" name="anioInicial" id="anioInicial" required>
                  <?php for ($k = $anio; $k >= 2010; $k--) : ?>
                    <option value="<?php echo $k; ?>" <?php echo ($anioInicial == $k) ? "selected" : "" ?>>
                      <?php echo $k ?></option>
                  <?php endfor; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-1">
              <div class="form-group">
                <label>Hasta:</label>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <select class="form-control form-control-sm" name="mesFinal" id="mesFinal" required>
                  <?php for ($mf = 1; $mf <= 12; $mf++) : ?>
                    <option value="<?php echo $mf; ?>" <?php echo ($mesFinal == $mf) ? "selected" : ""; ?>>
                      <?php echo $meses[$mf]; ?></option>
                  <?php endfor; ?>
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <select class="form-control form-control-sm" name="anioFinal" id="anioFinal" required>
                  <?php for ($k = $anio; $k >= 2010; $k--) : ?>
                    <option value="<?php echo $k; ?>" <?php echo ($anioFinal == $k) ? "selected" : ""; ?>><?php echo $k; ?></option>
                  <?php endfor; ?>
                </select>
              </div>
            </div>
          </div>
          <input type='hidden' name='action' id='action' value="gastosruta/index.php" />
          <div class="row">
            <div class="col-md-2">
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
  <!-- Nuevo -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Header -->
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Lista de gastos</h6>
      </div>
      <!-- Card Body -->
      <div class="card-body">
        <div class="row">
          <div class="col-md-2 offset-md-10">
            <form action="../controller/Reportes/ReporteGastosRuta.php" method="POST">
              <input type="hidden" name="dia" value="">
              <input type="hidden" name="zona" value="<?php echo $zonaId ?>">
              <input type="hidden" name="mesInicial" value="<?php echo $mesInicial ?>">
              <input type="hidden" name="anioInicial" value="<?php echo $anioInicial ?>">
              <input type="hidden" name="mesFinal" value="<?php echo $mesFinal ?>">
              <input type="hidden" name="anioFinal" value="<?php echo $anioFinal ?>">
              <button class="btn btn-sm btn-primary" type="submit"><i class="far fa-file-pdf"></i>Guardar PDF</button>
            </form>
            <button class="btn btn-sm btn-warning" id="btnExport"><i class="far fa-file-excel"></i> Exportar Excel</button>
          </div>
        </div>
        <table id="listaTabla" class="table table-bordered table-sm table-responsive" style="width:100%">
          <thead>
            <tr>
              <th>Zona</th>
              <th>Ruta</th>
              <th>Concepto</th>
              <th>Año</th>
              <th>Mes</th>
              <th>Cantidad</th>
              <th>Observaciones</th>
              <th>Comprobante</th>
              <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "u" || $_SESSION["tipoUsuario"] == "ga") : ?>
                <th>Opciones</th>
              <?php endif; ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($gastos as $gasto) :
              $totalGastos += $gasto["cantidad"];
            ?>
            <?php
var_dump($gasto); // Para ver la estructura del arreglo
?>
              <tr>
                <td><?php echo $gasto["zona_nombre"] ?></td>
                <td><?php echo $gasto["ruta_gasto"] ?></td>
                <td><?php echo $gasto["concepto_gasto"] ?></td>
                <td><?php echo $gasto["anio"] ?></td>
                <td><?php echo $meses[$gasto["mes"]] ?></td>
                <td class="text-right">$<?php echo $gasto["cantidad"] ?></td>
                <td><?php echo $gasto["observaciones"] ?></td>
                <td>
                  
                <?php if (isset($gasto['comprobante_gasto']) && !empty($gasto['comprobante_gasto'])): ?>
                      <a href="https://cgtest.v2technoconsulting.com/view/gastosruta/comprobantes/<?php echo basename($gasto['comprobante_gasto']); ?>" download>
                          Descargar comprobante
                      </a>
                  <?php else: ?>
                      <span>Sin comprobante</span>
                  <?php endif; ?>

                </td>
                <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "u" || $_SESSION["tipoUsuario"] == "ga") : ?>
                  <td>
                    <button class='btn btn-sm btn-light' type='button' onclick="editar('<?php echo $gasto['idgasto']; ?>');"><i class='fas fa-pencil-alt'></i></i></button>
                    <button class='btn btn-sm btn-primary' type='button' onclick="eliminar('<?php echo $gasto['idgasto']; ?>');"><i class='fas fa-trash fa-sm'></i></button>
                  </td>
                <?php endif; ?>
              </tr>
            <?php endforeach; ?>
            <tr class="bg-light">
              <td><b>Total</b></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td class="text-right"><b>$<?php echo number_format($totalGastos, 2) ?></b></td>
              <td></td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script type="text/JavaScript">
  $(document).ready(function(){
    obtenerRutas();
  });

  $('#listaTabla').DataTable({
    "pageLength": 25,
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
    }
  }); 

  $("#btnExport").click(function (e) {
    $("#listaTabla").btechco_excelexport({
          containerid: "listaTabla"
          , datatype: $datatype.Table
          , filename: 'gastosalmacen'
    });
  });

  function obtenerRutas(){
    let tipoUsuario = "<?php echo $_SESSION["tipoUsuario"] ?>";

    let zonaId = 0;
    if(tipoUsuario == "su" || tipoUsuario == "uc" || tipoUsuario == "ga") zonaId = "<?php echo $zonaId ?>";
    else zonaId = "<?php echo $_SESSION["zonaId"] ?>";
    $("#ruta").empty().append('<option value="0" selected>Todas</option>');
    if(zonaId){
      $.ajax({
        data: { zonaId : zonaId },
        type: "GET",
        url: '../controller/Rutas/ObtenerPorZona.php', 
        dataType: "json",
        success: function(data){
          $.each(data,function(key, ruta) {
            let estatus_ruta = "";
            if(ruta.estatus == 0) estatus_ruta = "(INACTIVO)"; 
            $("#ruta").append('<option value='+ ruta.idruta + '>'+ ruta.clave_ruta +' '+estatus_ruta+'</option>');
          });
          let rutaId = "<?php echo $rutaId ?>";  
          if(rutaId) $("#ruta").val(rutaId).change();    
        },
        error: function(data) {
          alertify.error('Ha ocurrido un error al cargar las rutas de la zona.');
        }
      });
    }
  }

  function editar(id) {
    window.location.href = 'index.php?action=gastosruta/editar.php&id='+id;
  }

  function eliminar(id) {
    alertify.confirm("¿Realmente desea eliminar el gasto seleccionado?",
      function() {
        $.ajax({
            type: "POST",
            url: "../controller/Gastos/EliminarGasto.php",
            data: {
              id: id
            },
            success: function(data) {
              location.reload();
              alertify.success("Gasto eliminado exitosamente");
            }
          });
      },
      function() {
      })
      .set({
        title: "Eliminar gasto"
      })
      .set({
        labels: {
          ok: 'Aceptar',
          cancel: 'Cancelar'
        }
      });
  }
</script>