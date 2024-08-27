<?php
$modelCredito = new ModelCredito();
$modelCliente = new ModelCliente();
$tipoZona = $_SESSION["tipoZona"];
$zonaId = $_SESSION["zonaId"];

if ($_SESSION['tipoUsuario'] != "su" && $tipoZona != 1) {
  echo "<script> 
          alert('Tu zona no vende GAS... Redireccionando a créditos de gasolina');
          window.location.href = 'index.php?action=creditosgasolina/index.php';
        </script>";
}

$valor = (!empty($_GET["valor"])) ? $_GET["valor"] : "";
$tipoBusqueda = (!empty($_GET["tipoBusqueda"])) ? $_GET["tipoBusqueda"] : "cliente";

if (!empty($valor)) {
  $creditos = $modelCredito->obtenerCreditosGasOtorgadosCliente($tipoBusqueda, $valor);
  if($tipoBusqueda == "cliente"){
    $cliente = $modelCliente->buscarPorId($valor);
    $cliente = reset($cliente);
    $valor = $cliente["nombre_cliente"];
  }

  $importe = 0;
  $importe_pag = 0;
  $saldo = 0;

  foreach ($creditos as $credito) {
    $importe = $importe + $credito["importe"];
    $importe_pag = $importe_pag + $credito["importe_pagado"];
    $saldo = $importe - $importe_pag;
  }
}
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="index.php?action=creditos/index.php">Créditos Gas</a> /
    <a href="#">Capturar crédito recuperado</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Capturar crédito recuperado</h1>
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
        <div class="row">
          <div class="col-md-2">
            <label>Buscar por:</label>
          </div>
          <div class="col-md-2">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="tipoBusqueda" id="tipoBusqueda" value="cliente" checked>
              <label class="form-check-label">Nombre de cliente</label>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="tipoBusqueda" id="tipoBusqueda" value="factura">
              <label class="form-check-label">Número de factura</label>
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
            <input class="btn btn-sm btn-primary" type="button" value="Recuperar crédito" onclick="obtenerDatosCredito();">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php if (!empty($valor)) : ?>
  <div class="row">
    <div class="col-xl-12 col-lg-12">
      <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Créditos otorgados <?php echo $valor ?></h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
          <button class="btn btn-sm btn-warning" id="btnExport">Exportar a Excel</button>
          <div id="dvData">
            <table id="datosexcel">
              <tr><strong><label><?php echo "Total otorgados: $" . $importe ?></label></strong></tr><br>
              <tr><strong><label><?php echo "Abonos: $" . $importe_pag ?></label></strong></tr><br>
              <tr><strong><label><?php echo "Saldo: $" . $saldo ?></label></strong></tr>
            </table>
            <table id="datosexcel" class="table table-striped table-bordered table-sm table-responsive" style="width:100%">
              <thead>
                <tr>
                  <th>Fecha alta</th>
                  <th>Número factura</th>
                  <th>Folio fiscal</th>
                  <th>Nombre cliente</th>
                  <th>Litros</th>
                  <th>Precio</th>
                  <th>Importe</th>
                  <th>Importe abonado</th>
                  <th>Pendiente por pagar</th>
                  <th>Vencimiento</th>
                  <th>Recuperar</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($creditos as $credito) :
                  $resto = $credito["importe"] - $credito["importe_pagado"];
                ?>
                  <tr>
                    <td> <?php echo $credito["fecha"] ?></td>
                    <td> <?php echo $credito["num_factura"] ?></td>
                    <td> <?php echo $credito["folio_fiscal"] ?></td>
                    <td> <?php echo $credito["nombre"] ?></td>
                    <td> <?php echo $credito["litros"] ?></td>
                    <td> $<?php echo $credito["precio_litro"] ?></td>
                    <td> $<?php echo $credito["importe"] ?></td>
                    <td> $<?php echo $credito["importe_pagado"] ?></td>
                    <td> $<?php echo $resto ?></td>
                    <td> <?php echo $credito["fecha_vencimiento"] ?></td>
                    <td>
                      <button class="btn btn-sm btn-warning" type='button' data-toggle="tooltip" data-placement="top" title="Recuperar" onclick="recuperarCredito('<?php echo $credito['id_cliente']; ?>','<?php echo $credito['num_factura']; ?>');">
                        <i class="fas fa-hand-holding-usd"></i>
                        <i class="fas fa-arrow-down"></i>
                      </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
                <tr>
                  <th>Total</th>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td style="text-align:right">
                  </td>
                  <td style="text-align:right">
                  </td>
                  </td>
                  <td style="text-align:right">
                    $<?php echo number_format(($importe), 2) ?></strong>
                  </td>
                  <td style="text-align:right">
                    $<?php echo number_format(($importe_pag), 2) ?></strong>
                  </td>
                  <td></td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<script type="text/JavaScript">
  $(document).ready(function() {
    $("#btnExport").click(function(e) {
        $("#datosexcel").btechco_excelexport({
            containerid: "datosexcel",
            datatype: $datatype.Table,
            filename: 'creditosclientes'
        });
    });
  });

  $('#selectCliente').select2({
    placeholder: "Escribe el nombre de cliente/factura",
    minimumInputLength: 4,
    ajax: {
      url: function () {
        if($("input[name=tipoBusqueda]:checked").val() == "factura"){
          return '../controller/Clientes/BuscarClientesGasFactura.php';
        }
        else{
          return '../controller/Clientes/BuscarClientesGasNombre.php';
        }    
      },
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

  function obtenerDatosCredito(){
    var combo = document.getElementById('selectCliente');
    if(combo.selectedIndex<0){
        alertify.error('Selecciona un cliente');
    }
    else{
     var valor = $("#selectCliente").val();
     var tipoBusqueda = $("input[name=tipoBusqueda]:checked").val()
     window.location.href = 'index.php?action=creditos/buscar_recuperado_cliente.php&valor='+valor+'&tipoBusqueda='+tipoBusqueda;
    }
  }

  function recuperarCredito(idCliente,numFactura){
     window.location.href = 'index.php?action=creditos/capturar_recuperado_cliente.php&cliente='+idCliente+'&numFactura='+numFactura;
  }
</script>