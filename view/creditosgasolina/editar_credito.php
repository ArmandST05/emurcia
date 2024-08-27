<?php
$modelZona = new ModelZona();
$modelCredito = new ModelCredito();
$modelCliente = new ModelCliente();
require_once('calendar/classes/tc_calendar.php');

$id = $_GET['id'];
$datosCredito = reset($modelCredito->obtenerCreditoGasolinaId($id));
$clienteId = $datosCredito["id_cliente"];

if ($datosCredito["tipo"]) $tipoCredito = "Otorgado";
else $tipoCredito = "Recuperado";

$importeAnterior = $datosCredito["importe"];
$clienteDatos = $modelCliente->obtenerPorId($clienteId);
$zonas = $modelZona->obtenerZonasGasolina();
$anio = date("Y");
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="index.php?action=creditosgasolina/index.php">Créditos Gasolina</a> /
    <a href="index.php?action=creditosgasolina/administrar_cliente.php">Administrar</a> /
    <a href="#">Editar</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Editar Crédito Gasolina</h1>
</div>

<!-- Content Row -->
<div class="row">
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Header - Dropdown -->
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary"><?php echo $tipoCredito ?></h6>
      </div>
      <!-- Card Body -->
      <div class="card-body">
        <form action="../controller/CreditosGasolina/ActualizarCredito.php" method="POST" name="form1">
          <div class="row">
            <div class="col-md-8">
              <div class="alert alert-secondary" role="alert">
                *Nota: Los datos del cliente se cargan automáticamente<br>
                Las fechas son proporcionadas automáticamente
              </div>
              <div class="row">
                <table border="0">
                  <?php
                  $fecha = $datosCredito["fecha"];
                  $dia = substr($fecha, 8, 2);
                  $mes = substr($fecha, 5, 2);
                  $anio = substr($fecha, 0, 4);
                  ?>
                  <tr>
                    <td>Fecha ingresada: </td>
                    <td>
                      <?php
                      $myCalendar = new tc_calendar("date1", true);
                      $myCalendar->setDate($dia, $mes, $anio);
                      $myCalendar->setYearInterval(2010, $anio);
                      $myCalendar->setOnChange("calcularFecha()");
                      $myCalendar->writeScript();
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td>Nombre cliente: </td>
                    <td><input class="form-control form-control-sm" type="text" name="name" id="name" value="<?php echo $datosCredito["nombre"] ?>" readonly></td>
                  </tr>
                  <tr>
                    <td>Domicilio: </td>
                    <td><input class="form-control form-control-sm" type="text" name="dom" id="dom" value="<?php echo $datosCredito["domicilio"] ?>" readonly></td>
                  </tr>
                  <tr>
                    <td>Colonia: </td>
                    <td><input class="form-control form-control-sm" type="text" name="col" id="col" value="<?php echo $datosCredito["colonia"] ?>" readonly></td>
                  </tr>
                  <tr>
                    <td>Nota / Factura: </td>
                    <td><input class="form-control form-control-sm" type="text" name="nota" value="<?php echo $datosCredito["num_factura"] ?>" id="nota" readonly></td>
                  </tr>
                  <tr>
                    <td>Precio: </td>
                    <td>
                      <div class="form-group">
                        <div class="input-group input-group-sm mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                          </div>
                          <input type="number" class="form-control form-control-sm" name="pre" id="pre" value="<?php echo $datosCredito["precio_litro"] ?>" onChange="calcularprecio();" onkeypress="validate(event)">
                        </div>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>Litros: </td>
                    <td>
                      <div class="form-group">
                        <div class="input-group input-group-sm mb-3">
                          <input type="number" class="form-control" name="lit" id="lit" min="0" step=".01" value="<?php echo $datosCredito["litros"] ?>" onChange="calcularprecio();" onkeypress="validate(event)">
                          <div class="input-group-append">
                            <span class="input-group-text">Lts</span>
                          </div>
                        </div>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>Importe: </td>
                    <td>
                      <div class="form-group">
                        <div class="input-group input-group-sm mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                          </div>
                          <input type="number" class="form-control form-control-sm" name="imp" id="imp" value="<?php echo $datosCredito["importe"] ?>" min="0" step=".01" value="0" readonly>
                        </div>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>Vencimiento: </td>
                    <td><input class="form-control form-control-sm" type="text" value="<?php echo $datosCredito["fecha_vencimiento"] ?>" name="ven_date" id="ven_date" readonly></td>
                  </tr>
                  <input type="hidden" name="formatofecha" id="formatofecha">
                  <input type="hidden" name="id_credito" value="<?php echo $datosCredito["idcreditogasolina"] ?>" id="id_credito">
                  <input type="hidden" name="tipo_credito" value="<?php echo $datosCredito["tipo"] ?>" id="tipo_credito">
                  <input type="hidden" name="id_cliente" value="<?php echo $datosCredito["id_cliente"] ?>" id="id_cliente">
                  <input type="hidden" name="formfecha" id="formfecha">
                  <tr>
                    <td>Vendedor: </td>
                    <td><input class="form-control form-control-sm" type="text" name="salesman" id="salesman" value="<?php echo $datosCredito["vendedor"] ?>"></td>
                  </tr>
                  <input type="hidden" name="oldimp" value="<?php echo $importeAnterior ?>" id="oldimp">
                  <input type="hidden" name="zone" value="<?php echo $_SESSION["zonaId"] ?>" id="oldimp">
                  <tr>
                    <td>Zona: </td>
                    <td><select class="form-control form-control-sm" name="zone">
                        <?php foreach ($zonas as $datosZona) : ?>
                          <option value='<?php echo $datosZona["idzona"] ?>' <?php echo (strcmp($clienteDatos['zona_id'], $datosZona["idzona"]) == 0) ? 'selected' : ''; ?>>
                            <?php echo $datosZona["nombre"] ?></option>
                        <?php endforeach; ?>
                      </select></td>
                  </tr>
                </table>
                <input type="hidden" name="disp" value="<?php echo $clienteDatos["credit_actual"] ?>" id="disp">
                <input type="hidden" name="used" value="<?php echo $clienteDatos["credit_use"] ?>" id="used">
                <input type="hidden" name="day" id="day">
                <input type="hidden" name="month" id="month">
                <input type="hidden" name="year" id="year">
              </div>
            </div>
            <div class="col-md-4">
              <div class="card border-light mb-3" style="max-width: 18rem;">
                <div class="card-header"><i class="fas fa-info-circle fa-2x"></i></div>
                <div class="card-body">
                  <div class="row">
                    <label>Crédito disponible</label>
                  </div>
                  <div class="row">
                    <label><b>$<?php echo number_format($clienteDatos["credit_actual"], 2); ?></b></label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2 offset-md-10">
              <input class="btn btn-sm btn-primary" type="submit" onClick="agregarFecha();" value="Guardar">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/JavaScript">
  $(document).ready(function(){

  });

  function agregarFecha(){
    var d = new Date();
    var n = d.toISOString().slice(0,10).split("-").join("/");
    var day = n.slice(8,10);
    var month = n.slice(5,7);
    var year = n.slice(0,4);
    calcularFecha();
  }

  function calcularFecha(){
    //Cortamos la fecha ingresada en su dia, mes y año
    var date_cap = document.form1.date1.value;
    var dia = date_cap.slice(8,10);
    var mes = date_cap.slice(5,7);
    var ano = date_cap.slice(0,4);
    document.getElementById('formfecha').value = ano + "/" + mes + "/" + dia;
    //Calculamos la fecha
    var myDate2 = new Date(ano,mes -1 ,dia);
    myDate2.setDate(myDate2.getDate() + 10);
    var date_calc = myDate2.toISOString().slice(0,10).split("-").join("/");
    document.getElementById('formatofecha').value = myDate2.toISOString().slice(0,10).split("-").join("/");
    //Cortamos la fecha calculada para la impresion
    var diacalc = date_calc.slice(8,10);
    var mescalc = date_calc.slice(5,7);
    var anocalc = date_calc.slice(0,4);
    document.getElementById('ven_date').value = diacalc + "/" + mescalc + "/" + anocalc;

    document.getElementById('day').value = dia;
    document.getElementById('month').value = mes;
    document.getElementById('year').value = ano;
  }

  function calcularprecio(){
    var precio = document.getElementById('pre').value;
    var litros = document.getElementById('lit').value;
    document.getElementById('imp').value = parseFloat(eval(precio*litros)).toFixed(2);
  }

  function validate(evt) {
    var theEvent = evt || window.event;
    var key = theEvent.which;
    key = String.fromCharCode( key );
    var regex = /[0-9]|\./;
    var regex2 =   /[ -~]/;
    if( regex2.test(key) && !regex.test(key) ) {
            theEvent.returnValue = false;
            if(theEvent.preventDefault){
                theEvent.preventDefault();
            }
    }  
  }
</script>