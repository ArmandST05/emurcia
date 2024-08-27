<?php
$modelCredito = new ModelCredito();
$modelCliente = new ModelCliente();
$tipoZona = $_SESSION["tipoZona"];
$zonaId = $_SESSION["zonaId"];

if ($_SESSION['tipoUsuario'] != "su") {
  echo "<script> 
          alert('No tienes acceso a esta sección');
          window.location.href = 'index.php?action=creditos/index.php';
        </script>";
}

$valor = (!empty($_GET["valor"])) ? $_GET["valor"] : "";
$tipoBusqueda = (!empty($_GET["tipoBusqueda"])) ? $_GET["tipoBusqueda"] : "cliente";

if (!empty($valor)) {
  $creditos = $modelCredito->seleccionarcredito_clientesgasolina($valor);
  $cliente = $modelCliente->buscarPorId($valor);
  $cliente = (!empty($cliente)) ? reset($cliente) : "";
}

if (!empty($valor)) {
  if ($tipoBusqueda == "factura") {
    $creditos = $modelCredito->obtenerCreditosGasolinaFactura($valor);
  } else {
    $creditos = $modelCredito->obtenerCreditosGasolinaCliente($valor);
    $cliente = $modelCliente->buscarPorId($valor);
    $cliente = reset($cliente);
    $valor = $cliente['nombre_cliente'];
  }
}

?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="index.php?action=creditosgasolina/index.php">Créditos Gasolina</a> /
    <a href="#">Administrar</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Administrar créditos</h1>
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
              <input class="form-check-input" type="radio" name="tipoBusqueda" value="cliente" checked>
              <label class="form-check-label">Nombre de cliente</label>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="tipoBusqueda" value="factura">
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
            <input class="btn btn-sm btn-primary" type="button" value="Buscar" onclick="obtenerDatosCredito();">
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
          <h6 class="m-0 font-weight-bold text-primary">Créditos <?php echo $valor ?></h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
          <div class="row">
            <table id="listaTabla" class="table table-bordered table-sm table-responsive" style="width:100%">
              <thead>
                <tr>
                  <th>Fecha alta</th>
                  <th>No. factura</th>
                  <th>Nombre cliente</th>
                  <th>Importe</th>
                  <th>Vencimiento</th>
                  <th>Tipo de crédito</th>
                  <th>Acción</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($creditos as $credito) :
                ?>
                  <tr>
                    <td> <?php echo $credito["fecha"] ?></td>
                    <td> <?php echo $credito["num_factura"] ?></td>
                    <td> <?php echo $credito["nombre"] ?></td>
                    <td> $<?php echo $credito["importe"] ?></td>
                    <td> <?php echo $credito["fecha_vencimiento"] ?></td>
                    <td><?php
                        if ($credito["tipo"] == 0) {
                          echo "Otorgado";
                        } else if ($credito["tipo"] == 1) {
                          echo "Recuperado";
                        } ?></td>
                    </td>
                    <td>
                      <!--<button class='btn btn-sm btn-light' type='button' onclick="editar('<?php echo $credito['idcreditogasolina']; ?>');"><i class='fas fa-pencil-alt fa-sm'></i></i></button>-->
                      <button class='btn btn-sm btn-primary' type='button' onclick="eliminar('<?php echo $credito['idcreditogasolina']; ?>');"><i class='fas fa-trash fa-sm'></i></button>
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
<?php endif; ?>

<script type="text/JavaScript">
  $(document).ready(function() {
  });

  $('#listaTabla').DataTable({
    "pageLength": 50,
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
    }
  });

  $('#selectCliente').select2({
    placeholder: "Escribe el nombre de cliente/factura",
    minimumInputLength: 4,
    ajax: {
      url: function () {
        if($("input[name=tipoBusqueda]:checked").val() == "factura"){
          return '../controller/Clientes/BuscarClientesGasolinaFactura.php';
        }
        else{
          return '../controller/Clientes/BuscarClientesGasolinaNombre.php';
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
     var tipoBusqueda = $("input[name=tipoBusqueda]:checked").val();
     window.location.href = 'index.php?action=creditosgasolina/administrar_cliente.php&valor='+valor+'&tipoBusqueda='+tipoBusqueda;
    }
  }

  function editar(id) {
    window.location.href = 'index.php?action=creditosgasolina/editar_credito.php&id='+id;
  }

  function eliminar(id) {
    alertify.confirm("¿Realmente desea eliminar el crédito seleccionado?",
      function() {
        $.ajax({
            type: "POST",
            url: "../controller/CreditosGasolina/EliminarCredito.php",
            data: {
              id: id
            },
            success: function(data) {
              alertify.success("Crédito eliminado");
              //location.reload();
            }
          });
      },
      function() {
      })
      .set({
        title: "Eliminar crédito"
      })
      .set({
        labels: {
          ok: 'Aceptar',
          cancel: 'Cancelar'
        }
      });
  }
</script>