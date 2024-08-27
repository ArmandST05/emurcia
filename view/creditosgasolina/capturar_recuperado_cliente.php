<?php
$modelCredito = new ModelCredito();
$modelCliente = new ModelCliente();
require('calendar/classes/tc_calendar.php');

if ($_SESSION["tipoUsuario"] != "su" && $_SESSION["tipoZona"] != 2) {
  echo "<script> 
          alert('Tu zona no vende GASOLINA... Redireccionando a créditos de gas');
          window.location.href = 'index.php?action=creditos/index.php';
        </script>";
}

$idCliente = $_GET["cliente"];
$idFac = $_GET["numFactura"];

$cliente = $modelCliente->buscarPorId($idCliente);
$factura = $modelCredito->seleccionarfacturagasolina($idCliente, $idFac);

if (empty($cliente) || empty($factura)) {
  echo "<script> 
          alertify.error('Los datos proporcionados son incorrectos');
          window.location.href = 'index.php?action=creditosgasolina/buscar_recuperado_cliente.php';
        </script>";
} else {
  $cliente = reset($cliente);
  $factura = reset($factura);
  $zona = $cliente["zona_id"];
  $restoPagar = $factura["importe"] - $factura["importe_pagado"];
}

$day = date("d");
$mes = date("m");
$anio = date("Y");
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="index.php?action=creditosgasolina/index.php">Créditos Gasolina</a> /
    <a href="index.php?action=creditosgasolina/buscar_recuperado_cliente.php">Capturar crédito recuperado</a> /
    <a href="#">Factura <?php echo $idFac ?></a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Capturar crédito recuperado</h1>
</div>

<!-- Content Row -->
<div class="row">
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Body -->
      <form action="../controller/CreditosGasolina/InsertarCreditoRecuperado.php" method="POST" name="form1">
        <div class="card-body">
          <div class="row">
            <div class="col-md-8">
              <div class="alert alert-secondary" role="alert">
                *Nota: Los datos del cliente se cargan automáticamente
              </div>
              <div class="row">
                <div class="col-md-2">
                  <label>Fecha</label>
                </div>
                <div class="col-md-2">
                  <select class="form-control form-control-sm" name="diaini" id="diaini" onChange="calcularFecha();">
                    <?php
                    for ($i = 1; $i <= 31; $i++) {
                      echo "<option value=" . $i;
                      if ($day == $i) {
                        echo " selected='selected'";
                      }
                      echo ">" . $i . "</option>";
                    }
                    ?>
                  </select>
                </div>
                <div class="col-md-2">
                  <select class="form-control form-control-sm" name="mesini" id="mesini" onChange="calcularFecha();">
                    <?php
                    $meses = ["1" => "Enero", "2" => "Febrero", "3" => "Marzo", "4" => "Abril", "5" => "Mayo", "6" => "Junio", "7" => "Julio", "8" => "Agosto", "9" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];
                    for ($j = 1; $j <= 12; $j++) {
                      echo "<option value=" . $j;
                      if ($mes == $j) {
                        echo " selected='selected'";
                      }
                      echo ">" . $meses[$j] . "</option>";
                    }
                    ?>
                  </select>
                </div>
                <div class="col-md-2">
                  <select class="form-control form-control-sm" name="anioini" id="anioini" onChange="calcularFecha();">
                    <?php
                    for ($k = date('Y'); $k >= 2012; $k--) {
                      echo "<option value=" . $k;
                      if ($anio == $k) {
                        echo " selected='selected'";
                      }
                      echo ">" . $k . "</option>";
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">
                  <label>Nombre cliente</label>
                </div>
                <div class="col-md-6">
                  <input class="form-control form-control-sm" type="text" name="name" id="name" value="<?php echo $cliente["nombre_cliente"] ?>" readonly>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">
                  <label>Domicilio</label>
                </div>
                <div class="col-md-6">
                  <input class="form-control form-control-sm" type="text" name="dom" id="dom" value="<?php echo $cliente["domicilio"] ?>" readonly>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">
                  <label>Colonia</label>
                </div>
                <div class="col-md-6">
                  <input class="form-control form-control-sm" type="text" name="col" id="col" value="<?php echo $cliente["colonia"] ?>" readonly>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">
                  <label>Nota/Factura</label>
                </div>
                <div class="col-md-6">
                  <input class="form-control form-control-sm" type="text" name="nota" id="nota" value="<?php echo $factura["num_factura"] ?>" readonly>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">
                  <label>Folio Fiscal</label>
                </div>
                <div class="col-md-6">
                  <input class="form-control form-control-sm" type="text" name="pre" id="pre" readonly value="<?php echo $factura["folio_fiscal"] ?>">
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">
                  <label>Precio</label>
                </div>
                <div class="col-md-6">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text">$</span>
                    </div>
                    <input class="form-control" type="text" name="pre" id="pre" readonly value='<?php echo $factura["precio_litro"] ?>'>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">
                  <label>Litros</label>
                </div>
                <div class="col-md-6">
                  <div class="input-group input-group-sm mb-3">
                    <input class="form-control form-control-sm" type="text" name="lit" id="lit" readonly value="<?php echo $factura["litros"] ?>">
                    <div class="input-group-append">
                      <span class="input-group-text">lts</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">
                  <label>Importe</label>
                </div>
                <div class="col-md-6">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text">$</span>
                    </div>
                    <input class="form-control form-control-sm" type="text" name="imp" id="imp" onkeydown="return decimales(this, event)">
                  </div>
                  <label>*El importe de esta factura es: $<?php echo $factura["importe"] ?> | Abonado hasta el momento$: <?php echo $factura["importe_pagado"] ?></label>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">
                  <label>Vencimiento</label>
                </div>
                <div class="col-md-6">
                  <input class="form-control form-control-sm" type="text" name="ven_date" id="ven_date" readonly>
                  <label>*Esta factura vence el <?php echo $factura["fecha_vencimiento"] ?></label>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">
                  <label>Vendedor</label>
                </div>
                <div class="col-md-6">
                  <input class="form-control form-control-sm" type="text" name="salesman" id="salesman" readonly value="<?php echo $factura["vendedor"] ?>">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card border-light mb-3" style="max-width: 18rem;">
                <div class="card-header"><i class="fas fa-info-circle fa-2x"></i></div>
                <div class="card-body">
                  <div class="row">
                    <h6>Crédito disponible</h6>
                  </div>
                  <div class="row">
                    <h6><b>$<?php echo number_format($cliente["credit_actual"],2); ?></b></h6>
                  </div>
                  <div class="row">
                    <h6>Límite de crédito</h6>
                  </div>
                  <div class="row">
                    <h6><b>$<?php echo number_format($cliente["credit_otor"],2); ?></b></h6>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <input type="hidden" name="disp" id="disp" value="<?php echo $cliente["credit_actual"] ?>">
          <input type="hidden" name="used" id="used" value="<?php echo $cliente["credit_use"] ?>">
          <input type="hidden" name="limit" id="limit" value="<?php echo $cliente["credit_otor"] ?>">

          <input type="hidden" name="oldimpor" id="oldimpor" value="<?php echo $factura["importe"] ?>">
          <input type="hidden" name="imporpag" id="imporpag" value="<?php echo $factura["importe_pagado"] ?>">
          <input type="hidden" name="imporact" id="imporact" value="<?php echo $factura["importe_pagado"] ?>">

          <input type="hidden" name="formatofecha" id="formatofecha">
          <input type="hidden" name="idcliente" id="idcliente" value="<?php echo $_GET["cliente"]; ?>">
          <input type="hidden" name="formfecha" id="formfecha">
          <input type="hidden" name="day" id="day">
          <input type="hidden" name="month" id="month">
          <input type="hidden" name="year" id="year">
          <input type="hidden" name="zona" id="zona" value="<?php echo $zona; ?>">

          <div class="row">
            <div class="col-md-2 offset-md-10">
              <input class="btn btn-sm btn-primary" type="submit" value="Guardar" onClick="return verificarLimite();">
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/JavaScript">
  $(document).ready(function(){
    calcularFecha();
  });

  $("#btnExport").click(function (e) {
    $("#datosexcel").btechco_excelexport({
        containerid: "datosexcel"
      , datatype: $datatype.Table
      , filename: 'creditosclientesgasolina'
    });
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
    var dia = document.getElementById('diaini').value;
    var mes = document.getElementById('mesini').value;
    var ano = document.getElementById('anioini').value;
    document.getElementById('formfecha').value = ano + "/" + mes + "/" + dia;
    //Calculamos la fecha
    var myDate2 = new Date(ano,mes -1 ,dia);
    myDate2.setDate(myDate2.getDate() - 10);
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
  var objeto2;  

  function decimales(objeto, e){               
    var keynum           
    var keychar           
    var numcheck          
    if(window.event){
      /*/ IE*/            
    keynum = e.keyCode         
    }          
    else if(e.which){ 
    /*/ Netscape/Firefox/Opera/*/          
    keynum = e.which         
    }            
    if((keynum>=35 && keynum<=37) ||keynum==8||keynum==9||keynum==46||keynum==39) {
                return true;         
    }          
    if(keynum==190||keynum==110||(keynum>=95&&keynum<=105)||(keynum>=48&&keynum<=57)){
      posicion = objeto.value.indexOf('.');               
      if(posicion==-1) {              
      return true;           
      }else { 
      if(!(keynum==190||keynum==110)){
      objeto2=objeto;
      t = setTimeout('dosDecimales()',150);
      return true;
      }else{
      objeto2=null;
      return false;
      }
    }
    }else {
    return false;
    }        
  }
 
  function dosDecimales(){    
    var objeto = objeto2;
    var posicion = objeto.value.indexOf('.');
    var decimal = 2;
    if(objeto.value.length - posicion < decimal){
      objeto.value = objeto.value.substr(0,objeto.value.length-1);                                        
    }else {
      objeto.value = objeto.value.substr(0,posicion+decimal+1);                                            
    }
    return;
  }
 
  function enteros(objeto, e){
    var keynum
    var keychar
    var numcheck
    if(window.event){ /*/ IE*/
    keynum = e.keyCode
    }
    else if(e.which){ /*/ Netscape/Firefox/Opera/*/
    keynum = e.which
    }
    if((keynum>=35 && keynum<=37) ||keynum==8||keynum==9||keynum==46||keynum==39) {
    return true;
    }
    if((keynum>=95&&keynum<=105)||(keynum>=48&&keynum<=57)){
    return true;
    }else {
    return false;
    }
  }  

  function verificarLimite() {
    var limit = document.getElementById('limit').value;
    var impor = document.getElementById('imp').value;
    var use = document.getElementById('used').value;
    var dispo = document.getElementById('disp').value;

    var new_credit = parseFloat(eval(parseFloat(impor) + parseFloat(dispo))).toFixed(2);

    if(parseFloat(new_credit) > parseFloat(limit)){
        alertify.error("El crédito excede el límite, verifica los datos");
        return false;
    }else{
        var importecorrecto = verificarImporte();
        if(importecorrecto == true){
            return true;
        }else{
            alertify.error("Estás ingresando un importe mayor al importe de la factura");
            return false;
        }
    }
  }

  function verificarImporte(){
    var abono = document.getElementById('imporpag').value; //Abonado hasta el momento
    var impor = document.getElementById("imp").value; //Importe capturado
    var oldimpor = document.getElementById("oldimpor").value;//Importe original
    var newimpor = eval(parseFloat(abono) + parseFloat(impor)).toFixed(2);
    if(parseFloat(newimpor) > parseFloat(oldimpor)){
      return false;
    }else{
      document.getElementById('imporact').value = newimpor;
      return true;
    }
  }
</script>