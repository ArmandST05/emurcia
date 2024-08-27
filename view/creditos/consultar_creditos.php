<?php
$modelCredito = new ModelCredito();
$idCliente = (!empty($_GET["cliente"])) ? $_GET["cliente"] : "";
$tipoCredito = (!empty($_GET["tipoBusqueda"])) ? $_GET["tipoBusqueda"] : "todos";

if (!empty($idCliente)) {
  //Revisamos los tipos:
  if ($tipoCredito == "otorgados") {
    $info = $modelCredito->obtenerCreditosOtorgadosGasCliente($idCliente);
  } else if ($tipoCredito == "recuperados") {
    $info = $modelCredito->obtenerCreditosRecuperadosGasCliente($idCliente);
    $abonos = $modelCredito->obtenerreportesabonos_cli($idCliente);
  } else if ($tipoCredito == "todos") {
    $info = $modelCredito->traercreditoscliente($idCliente);
    $abonos = $modelCredito->obtenerreportesabonos_cli($idCliente);
  }
}
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="index.php?action=creditos/index.php">Créditos Gas</a> /
    <a href="#">Consultar créditos</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Consultar créditos</h1>
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
          <div class="col-md-2">
            <label>Buscar por:</label>
          </div>
          <div class="col-md-2">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="tipoBusqueda" id="tipoBusqueda" value="otorgados" checked>
              <label class="form-check-label">Otorgados</label>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="tipoBusqueda" id="tipoBusqueda" value="recuperados">
              <label class="form-check-label">Recuperados</label>
            </div>
          </div>
          <div class="col-md-1">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="tipoBusqueda" id="tipoBusqueda" value="todos">
              <label class="form-check-label">Todos</label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-2">
            <label>Seleccione el cliente:</label>
          </div>
          <div class="col-md-8">
            <select class="form-control form-control-sm" id="selectCliente">
            </select>
          </div>
          <div class="col-md-2">
            <input class="btn btn-sm btn-primary" type="button" value="Consultar Créditos" onclick="obtenerDatosCredito();">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Content Row -->
<?php if (!empty($idCliente)) : ?>
  <div class="row">
    <div class="col-xl-12 col-lg-12">
      <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Lista de créditos</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
          <table id="listaCreditos" class="table table-bordered table-sm table-responsive" style="width:100%">
            <thead>
              <tr>
                <th>Fecha alta</th>
                <th>Número factura</th>
                <th>Nombre cliente</th>
                <th>Importe</th>
                <th>Vencimiento</th>
                <th>Tipo de crédito</th>
                <th>Vendedor</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($info as $datos) : ?>
                <tr>
                  <td><?php echo $datos["fecha"] ?></td>
                  <td><?php echo $datos["num_factura"] ?></td>
                  <td><?php echo $datos["nombre"] ?></td>
                  <td>$<?php echo $datos["importe"] ?></td>
                  <td><?php echo $datos["fecha_vencimiento"] ?></td>
                  <td><?php
                      if ($datos["tipo"] == 0) echo "Otorgado";
                      else if ($datos["tipo"] == 1) echo "Recuperado";
                      ?>
                  </td>
                  <td><?php echo $datos["vendedor"] ?>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-xl-12 col-lg-12">
      <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Créditos recuperados parciales</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
          <table id="listaCreditosParciales" class="table table-bordered table-sm table-responsive" style="width:100%">
            <thead>
              <tr>
                <th>Fecha</th>
                <th>Id Cliente</th>
                <th>Nombre cliente</th>
                <th>Número factura</th>
                <th>Importe</th>
                <th>Zona</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($tipoCredito != "otorgados") :
                foreach ($abonos as $datos) :
              ?>
                  <tr>
                    <td><?php echo $datos["fecha"] ?></td>
                    <td><?php echo $datos["cliente"] ?></td>
                    <td><?php echo $datos["nombre"] ?></td>
                    <td><?php echo $datos["nota"] ?></td>
                    <td>$<?php echo $datos["cantidad"] ?></td>
                    <td><?php echo $datos["zona_id"] ?></td>
                  </tr>
              <?php endforeach;
              endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>
<script type="text/JavaScript">
  $(document).ready(function() {
    $('#listaCreditos').DataTable({
      "pageLength": 25,
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
      }
    });
    $('#listaCreditosParciales').DataTable({
      "pageLength": 25,
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
      }
    });
  });

  $('#selectCliente').select2({
    placeholder: "Escribe el nombre de cliente/comercial",
    minimumInputLength: 4,
    ajax: {
      url: '../controller/Clientes/BuscarClientesNombre.php',
      type: 'GET',
      dataType: 'json',
      delay: 250,
      processResults : function (data) {
        return {
            results : data
        }
      }
    }
  });

  //Cuando oprime el botón de capturar crédito se busca el cliente y los datos
  function obtenerDatosCredito(){
    var combo = document.getElementById('selectCliente');
    var tipoBusqueda = $('input[name=tipoBusqueda]:checked').val();
    if(combo.selectedIndex<0 || tipoBusqueda == ""){
        alertify.error('Selecciona los datos para la búsqueda');
    }
    else{
     var idCliente = $("#selectCliente").val();
     window.location.href = 'index.php?action=creditos/consultar_creditos.php&cliente='+idCliente+'&tipoBusqueda='+tipoBusqueda;
    }
  }

</script>