<?php
$modelCliente = new ModelCliente();
$idcliente = $_GET["id"];
$infocliente = $modelCliente->verificarId($idcliente);

foreach ($infocliente as $key) {
  $credito_usado = $key["credit_use"]; //Otorgado total
}
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="../view/index.php?action=clientes/index_credito.php">Clientes Crédito</a> /
    <a href="#">Editar</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Editar Cliente Crédito</h1>
</div>

<!-- Content Row -->
<div class="row">
  <!-- Nuevo Pedido -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Body -->
      <div class="card-body">
        <form action="../controller/Clientes/ActualizarCliente.php" method="post" id="uniuti">
          <div class="row">
            <table border="0">
              <?php foreach ($infocliente as $cliente) : ?>
                <tr>
                  <td>Nombre: </td>
                  <td><input class="form-control form-control-sm" type="text" name="nomcli" value="<?php echo $cliente["nombre_cliente"] ?>" onchange="calcularDisponible()"></td>
                </tr>
                <tr>
                  <td>Domicilio: </td>
                  <td><input class="form-control form-control-sm" type="text" name="dom" value="<?php echo $cliente["domicilio"] ?>" onchange="calcularDisponible()"></td>
                </tr>
                <tr>
                  <td>Colonia: </td>
                  <td><input class="form-control form-control-sm" type="text" name="col" value="<?php echo $cliente["colonia"] ?>" onchange="calcularDisponible()"></td>
                </tr>
                <tr>
                  <td>Nombre comercial: </td>
                  <td><input class="form-control form-control-sm" type="text" name="tipneg" value="<?php echo $cliente["nombre_comercial"] ?>" onchange="calcularDisponible()"></td>
                </tr>
                <tr>
                  <td>Credito otorgado: </td>
                  <td>
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text">$</span>
                      </div>
                      <input class="form-control form-control-sm" type="text" name="credit" id="credit" value="<?php echo $cliente["credit_otor"] ?>" onchange="calcularDisponible()" onkeypress="validate(event)">
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Precio con descuento: </td>
                  <td>
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text">$</span>
                      </div>
                      <input class="form-control form-control-sm" type="text" name="pre" value="<?php echo $cliente["precio_des"] ?>" onkeypress="validate(event)">
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </table>
            <input type="hidden" name="usado" id="usado" value="<?php echo $credito_usado ?>">
            <input type="hidden" name="new_disp" id="new_disp">
            <input type="hidden" name="id" value="<?php echo $_GET["id"]?>">
          </div>
          
          <div class="row">
            <div class="col-md-1 offset-md-11">
              <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    calcularDisponible();
  });

  function validate(evt) {
    var theEvent = evt || window.event;
    var key = theEvent.which;
    key = String.fromCharCode(key);
    var regex = /[0-9]|\./;
    var regex2 = /[ -~]/;
    if (regex2.test(key) && !regex.test(key)) {
      theEvent.returnValue = false;
      if (theEvent.preventDefault) {
        theEvent.preventDefault();
      }
    }
  }

  function calcularDisponible() {
    var new_limit = document.getElementById('credit').value;
    var used = document.getElementById('usado').value;
    document.getElementById('new_disp').value = eval(parseFloat(new_limit) - parseFloat(used));
  }
</script>