<?php
$modelZona = new ModelZona();
$modelPrecioProducto = new ModelPrecioProducto();
$modelCliente = new ModelCliente();

$zonaId = $_SESSION["zonaId"];
if ($_SESSION["tipoUsuario"] != "su" && $_SESSION["tipoZona"] != 1) {
  echo "<script> 
          alert('Tu zona no vende GAS... Redireccionando a créditos de gasolina');
          window.location.href = 'index.php?action=creditosgasolina/index.php';
        </script>";
}

//Datos corregidos
$idCliente = (!empty($_GET["cliente"])) ? $_GET["cliente"] : "";

if (!empty($idCliente)) {
  $cliente = $modelCliente->buscarPorId($idCliente);
  if (empty($cliente)) {
    echo "<script> 
            alert('El cliente solicitado no existe');
            window.location.href = '../../index.php?action=creditos/index.php';
          </script>";
  } else {
    $cliente = reset($cliente);
    $zonaClienteId  = $cliente["zona_id"];

    $meses = ["1" => "Enero", "2" => "Febrero", "3" => "Marzo", "4" => "Abril", "5" => "Mayo", "6" => "Junio", "7" => "Julio", "8" => "Agosto", "9" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];
    $mes = date("n");
    $dia = date("d");
    $anio = date("Y");
  }

  $precioMes = $modelPrecioProducto->obtenerPrecioGasZonaMes($anio, date("n"), $zonaClienteId, 0);

  if (empty($precioMes)) {
    echo "<script> 
            alert('Para capturar un crédito otorgado, necesitas capturar el precio del mes de la zona " . $zonaClienteNombre . ", da clic en Aceptar para agregar el precio');
            window.location.href = '../../index.php?action=creditos/index.php';
          </script>";
  } else {
    $precioMes = reset($precioMes);
    $precioMesZona = $precioMes["precio_kilo"];

    $precio = 0;
    $precioDescuento = $cliente["precio_des"];
    $precio = $precioMesZona - $precioDescuento;
  }
}

?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="index.php?action=creditos/index.php">Créditos Gas</a> /
    <a href="#">Capturar crédito otorgado</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Capturar crédito otorgado</h1>
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
            <label>Seleccione el cliente:</label>
          </div>
          <div class="col-md-8">
            <select class="form-control form-control-sm" id="selectCliente">
            </select>
          </div>
          <div class="col-md-2">
            <input class="btn btn-sm btn-primary" type="button" value="Capturar crédito" onclick="obtenerDatosCliente();">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php if (!empty($idCliente)) : ?>
  <div class="row">
    <div class="col-xl-12 col-lg-12">
      <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Indicar los datos</h6>
        </div>
        <!-- Card Body -->
        <form action="../controller/Creditos/InsertarCreditoOtorgado.php" method="POST" name="form1" enctype="multipart/form-data">
          <div class="card-body">
            <div class="row">
              <div class="col-md-8">
                <div class="alert alert-secondary" role="alert">
                  *Nota: Los datos del cliente se cargan automaticamente
                </div>

                <div class="row">
           <div class="col-md-8">
               
            <label for="comprobante">Subir Comprobante Credito Otorgado:</label>
            <input type="file" name="comprobante" id="comprobante" accept="image/*" required>
            
      <br>
            </div>
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
                        if ($dia == $i) {
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
                    <input class="form-control form-control-sm" type="text" name="nota" id="nota" onkeypress='validate(event)'>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-2">
                    <label>Folio Fiscal: </label>
                  </div>
                  <div class="col-md-6">
                    <input class="form-control form-control-sm" type="text" name="folfis" id="folfis">
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-2">
                    <label>Precio zona</label>
                  </div>
                  <div class="col-md-6">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text">$</span>
                      </div>
                      <input class="form-control" type="text" name="pre" id="pre" value='<?php echo $precioMesZona ?>' onChange="calcularPrecio();" onkeypress="return SoloNumerosDecimales3(event, '0.0', 2, 2);">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-2">
                    <label>Precio con descuento</label>
                  </div>
                  <div class="col-md-6">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text">$</span>
                      </div>
                      <input class="form-control form-control-sm" type="text" name="pre_des" id="pre_des" value='<?php echo $precioDescuento ?>' onChange="calcularPrecio();" maxlength="7" onkeypress="return SoloNumerosDecimales3(event, '0.0', 2, 2);">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-2">
                    <label>Litros</label>
                  </div>
                  <div class="col-md-6">
                    <div class="input-group input-group-sm mb-3">
                      <input class="form-control form-control-sm" type="text" name="lit" id="lit" onChange="calcularPrecio();" onkeypress='validate(event)'>
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
                      <input class="form-control form-control-sm" type="text" name="imp" id="imp">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-2">
                    <label>Descuento</label>
                  </div>
                  <div class="col-md-6">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text">$</span>
                      </div>
                      <input class="form-control form-control-sm" type="text" name="desc" id="desc">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-2">
                    <label>Vencimiento</label>
                  </div>
                  <div class="col-md-6">
                    <input class="form-control form-control-sm" type="text" name="ven_date" id="ven_date" readonly>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-2">
                    <label>Vendedor</label>
                  </div>
                  <div class="col-md-6">
                    <input class="form-control form-control-sm" type="text" name="salesman" id="salesman">
                  </div>
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
                      <label><b>$<?php echo $cliente["credit_actual"]; ?></b></label>
                    </div>
                    <div class="row">
                      <label>Precio del mes <?php echo $meses[$mes] ?></label>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <label><b><?php echo $zonaClienteNombre ?></b></label>
                      </div>
                      <div class="col-md-6">
                        <label><b>$<?php echo $precioMes["precio_kilo"] ?></b></label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <input type="hidden" name="disp" id="disp" value="<?php echo $cliente["credit_actual"] ?>">
            <input type="hidden" name="used" id="used" value="<?php echo $cliente["credit_use"] ?>">
            <input type="hidden" name="precio_des" id="precio_des" value=' <?php echo $precio ?>'>
            <input type="hidden" name="precio_zo" id="precio_zo" value='<?php echo $precioMesZona ?>'>

            <input type="hidden" name="zone" id="zone" value="<?php echo $zonaClienteId ?>">
            <input type="hidden" name="formatofecha" id="formatofecha">
            <input type="hidden" name="idcliente" id="idcliente" value="<?php echo $_GET["cliente"]; ?>">
            <input type="hidden" name="formfecha" id="formfecha">
            <input type="hidden" name="day" id="day">
            <input type="hidden" name="month" id="month">
            <input type="hidden" name="year" id="year">

            <div class="row">
              <div class="col-md-2 offset-md-10">
                <input class="btn btn-sm btn-primary" type="submit" value="Guardar">
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php endif; ?>

<script type="text/JavaScript">
  $(document).ready(function(){
    calcularFecha();
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
  function obtenerDatosCliente(){
    var combo = document.getElementById('selectCliente');
    if(combo.selectedIndex<0){
        alertify.error('Selecciona un cliente');
    }
    else{
     var idCliente = $("#selectCliente").val();
     window.location.href = 'index.php?action=creditos/capturar_otorgado_cliente.php&cliente='+idCliente;
    }
  }

  function agregarFecha(){
      var d = new Date();
      var n = d.toISOString().slice(0,10).split("-").join("/");
      var day = n.slice(8,10);
      var month = n.slice(5,7);
      var year = n.slice(0,4);
      calcularFecha();
  }

  function calcularFecha(){
    var dia = document.getElementById('diaini').value;
    var mes = document.getElementById('mesini').value;
    var ano = document.getElementById('anioini').value;
    document.getElementById('formfecha').value = ano + "/" + mes + "/" + dia;
    //Calculamos la fecha
    var myDate2 = new Date(ano,mes -1 ,dia);
    myDate2.setDate(myDate2.getDate() + 10);
    var date_calc = myDate2.toISOString().slice(0,10).split("-").join("/");
    document.getElementById('formatofecha').value = myDate2.toISOString().slice(0,10).split("-").join("/");;
    //Cortamos la fecha calculada para la impresion
    var diacalc = date_calc.slice(8,10);
    var mescalc = date_calc.slice(5,7);
    var anocalc = date_calc.slice(0,4);
    document.getElementById('ven_date').value = diacalc + "/" + mescalc + "/" + anocalc;
    document.getElementById('day').value = dia;
    document.getElementById('month').value = mes;
    document.getElementById('year').value = ano;
  }

  function calcularPrecio(){
    var precio = document.getElementById('pre').value;
    var precio_con_des = document.getElementById('pre_des').value;
    var precio_des = document.getElementById('precio_des').value;
    var litros = document.getElementById('lit').value;
    if (precio_con_des == 0){
        document.getElementById('desc').value = parseFloat(eval(precio_con_des*litros)).toFixed(2);
        document.getElementById('imp').value = parseFloat(eval(precio*litros)).toFixed(2);
    }else{
      document.getElementById('desc').value = parseFloat(eval(precio_des*litros)).toFixed(2);
      document.getElementById('imp').value = parseFloat(eval(precio_con_des*litros)).toFixed(2);
    }     
  }

  function SoloNumerosDecimales3(e, valInicial, nEntero, nDecimal) {
    var obj = e.srcElement || e.target;
    var tecla_codigo = (document.all) ? e.keyCode : e.which;
    var tecla_valor = String.fromCharCode(tecla_codigo);
    var patron2 = /[\d.]/;
    var control = (tecla_codigo === 46 && (/[.]/).test(obj.value)) ? false : true;
    var existePto = (/[.]/).test(obj.value);

    //el tab
    if (tecla_codigo === 8)
        return true;

    if (valInicial !== obj.value) {
      var TControl = obj.value.length;
      if (existePto === false && tecla_codigo !== 46) {
          if (TControl === nEntero) {
              obj.value = obj.value + ".";
          }
      }

      if (existePto === true) {
        var subVal = obj.value.substring(obj.value.indexOf(".") + 1, obj.value.length);

        if (subVal.length > 1) {
            return false;
        }
      }
      return patron2.test(tecla_valor) && control;
    }
    else {
      if (valInicial === obj.value) {
          obj.value = '';
      }
      return patron2.test(tecla_valor) && control;
    }
  }

  function validarPrecio(){
    var pre = document.getElementById('pre').value;
    var precio_zo = document.getElementById('precio_zo').value;
    if(pre == precio_zo){
      return true;
    }else{
      alert('El precio introducido es mayor o menor al precio del mes');
      return false;
    }
  }
</script>