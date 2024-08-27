<?php
$modelCliente = new ModelCliente();
$modelZona = new ModelZona();
$modelPrecioProducto = new ModelPrecioProducto();
$zona = $_SESSION["zonaId"];

if ($_SESSION["tipoUsuario"] != "su" && $_SESSION["tipoZona"] != 2) {
  echo "<script> 
          alert('Tu zona no vende GASOLINA... Redireccionando a créditos de gas');
          window.location.href = 'index.php?action=creditosgasolina/index.php';
        </script>";
}

$idCliente = (!empty($_GET["cliente"])) ? $_GET["cliente"] : "";

if (!empty($idCliente)) {
  $cliente = $modelCliente->buscarPorId($idCliente);
  if (empty($cliente)) {
    echo "<script> 
    alert('El cliente solicitado no existe');
    window.location.href = 'index.php?action=creditosgasolina/capturar_otorgado_cliente.php';
      </script>";
  } else {
    $cliente = reset($cliente);
    $zonaCliente = $cliente["zona_id"];

    $zonas = $modelZona->obtenerZonasGasolina();

    $tipo = (!empty($_GET["tipo"])) ? $_GET["tipo"] : "magna";
    $nf = (!empty($_GET["nf"])) ? $_GET["nf"] : "";
    $ff = (!empty($_GET["ff"])) ? $_GET["ff"] : "";
    $prec = (!empty($_GET["prec"])) ? $_GET["prec"] : "";
    $li = (!empty($_GET["li"])) ? $_GET["li"] : "";

    $aceites = $modelCliente->obtenerAceites($zonaCliente);

    $day = date("d");
    $mes = date("m");
    $anio = date("Y");

    $mag = 0;$ieps_mag = 0;
    $pre = 0;$ieps_pre = 0;
    $die = 0;$ieps_die = 0;

    $magna = $modelPrecioProducto->obtenerPrecioGasolinaZonaProductoId($zonaCliente,6);
    foreach ($magna as $key) {
      $mag = $key["precio"];
      $ieps_mag = $key["ieps"];
    }
    $premium = $modelPrecioProducto->obtenerPrecioGasolinaZonaProductoId($zonaCliente,7);
    foreach ($premium as $key) {
      $pre = $key["precio"];
      $ieps_pre = $key["ieps"];
    }
    $diesel = $modelPrecioProducto->obtenerPrecioGasolinaZonaProductoId($zonaCliente,8);
    foreach ($diesel as $key) {
      $die = $key["precio"];
      $ieps_die = $key["ieps"];
    }
  }
}

?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="index.php?action=creditosgasolina/index.php">Créditos Gasolina</a> /
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
        <form action="../controller/CreditosGasolina/InsertarCreditoOtorgado.php" method="POST" name="form1">
          <div class="card-body">
            <div class="row">
              <div class="col-md-8">
                <div class="alert alert-secondary" role="alert">
                  *Nota: Los datos del cliente se cargan automáticamente<br>
                  Las fechas son proporcionadas automáticamente
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
                      for ($k = date("Y"); $k >= 2010; $k--) {
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
                    <input class="form-control form-control-sm" type="text" name="nota" id="nota" value="<?php echo $nf ?>" onkeypress='validate(event)' required>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-2">
                    <label>Folio Fiscal: </label>
                  </div>
                  <div class="col-md-6">
                    <input class="form-control form-control-sm" type="text" name="folfis" id="folfis" value="<?php echo $ff ?>" required>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-2">
                    <label>Tipo</label>
                  </div>
                  <div class="col-md-6">
                    <div class="row">
                      <div>
                        <input type="radio" id="mag" name="tipoProducto" value="magna" <?php echo ($tipo == "magna") ? "checked" : "" ?> onchange="calcularCosto(),calcularPrecio(),cambiarTipoProducto();">
                        <label>Magna</label>
                      </div>
                      <div>
                        <input type="radio" id="premi" name="tipoProducto" value="premium" <?php echo ($tipo == "premium") ? "checked" : "" ?> onchange="calcularCosto(),calcularPrecio(),cambiarTipoProducto();">
                        <label>Premium</label>
                      </div>
                      <div>
                        <input type="radio" id="die" name="tipoProducto" value="diesel" <?php echo ($tipo == "diesel") ? "checked" : "" ?> onchange="calcularCosto(),calcularPrecio(),cambiarTipoProducto();">
                        <label>Diesel</label>
                      </div>
                      <div>
                        <input type="radio" id="ace" name="tipoProducto" value="aceite" <?php echo ($tipo == "aceite") ? "checked" : "" ?> onchange="calcularCosto(),calcularPrecio(),cambiarTipoProducto();">
                        <label>Aceite</label>
                      </div>
                    </div>
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
                      <input class="form-control" type="text" name="pre" id="pre" value="" value="<?php echo $prec ?>" onChange="calcularPrecio(),calcularCosto();" onkeypress="return soloNumerosDecimales3(event, '0.0', 2, 2);" required>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-2">
                    <label>Litros</label>
                  </div>
                  <div class="col-md-6">
                    <div class="input-group input-group-sm mb-3">
                      <input class="form-control form-control-sm" type="text" name="lit" id="lit" value="<?php echo $li ?>" onChange="calcularPrecio();calcularCosto();" onkeypress='validate(event)' required>
                      <div class="input-group-append">
                        <span class="input-group-text">lts</span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-2">
                    <label>Aceite</label>
                  </div>
                  <div class="col-md-6">
                    <select class="form-control form-control-sm" id="aceite" name="aceite" required>
                      <?php
                        echo "<option value='" . '0' . "'</option>";
                        foreach ($aceites as $data) {
                          echo "<option value='" . $data["idaceite"] . "'";

                          echo ">" . $data["nombre"] . "</option>";
                        }     
                      ?>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-2">
                    <label>IEPS</label>
                  </div>
                  <div class="col-md-6">
                    <input class="form-control form-control-sm" type="text" name="ieps" id="ieps" readonly>
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
                      <input class="form-control form-control-sm" type="text" name="imp" id="imp" readonly>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-2">
                    <label>IVA</label>
                  </div>
                  <div class="col-md-6">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text">$</span>
                      </div>
                      <input class="form-control form-control-sm" type="text" name="iva" id="iva" onkeypress='validate(event)' readonly>
                    </div>
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
                    <label>Zona</label>
                  </div>
                  <div class="col-md-6">
                    <select class="form-control form-control-sm" id="zone" name="zone" <?php echo ($_SESSION['tipoUsuario'] != "su") ? "disabled": ""?> >
                      <?php
                      foreach ($zonas as $data) {
                        echo "<option value='" . $data["idzona"] . "'";
                        if (strcmp($zonaCliente, $data["idzona"]) == 0) {
                          echo " selected='selected'";
                        }
                        echo ">" . $data["nombre"] . "</option>";
                      }
                      ?>
                    </select>
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
                      <label><b>$<?php echo number_format($cliente["credit_actual"]); ?></b></label>
                    </div>
                    <div class="row">
                      <label>MAGNA</label>
                    </div>
                    <div class="row">
                      <label><b><?php echo "Precio: $" . number_format($mag, 2) . " IEPS:" . $ieps_mag; ?></b></label>
                    </div>
                    <div class="row">
                      <label>PREMIUM</label>
                    </div>
                    <div class="row">
                      <label><b><?php echo "Precio: $" . number_format($pre, 2) . " IEPS:" . $ieps_pre; ?></b></label>
                    </div>
                    <div class="row">
                      <label>DIESEL</label>
                    </div>
                    <div class="row">
                      <label><b><?php echo "Precio: $" . number_format($die, 2) . " IEPS:" . $ieps_die; ?></b></label>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <input type="hidden" name="pre_magna" id="pre_magna" value="<?php echo $mag; ?>">
            <input type="hidden" name="pre_premium" id="pre_premium" value="<?php echo $pre; ?>">
            <input type="hidden" name="pre_diesel" id="pre_diesel" value="<?php echo $die; ?>">
            <input type="hidden" name="ieps_magna" id="ieps_magna" value="<?php echo $ieps_mag; ?>">
            <input type="hidden" name="ieps_premium" id="ieps_premium" value="<?php echo $ieps_pre; ?>">
            <input type="hidden" name="iepsdiesel" id="ieps_diesel" value="<?php echo $ieps_die; ?>">

            <input type="hidden" name="disp" id="disp" value="<?php echo $cliente["credit_actual"] ?>">
            <input type="hidden" name="used" id="used" value="<?php echo $cliente["credit_use"] ?>">

            <input type="hidden" name="ivaimpsinieps" id="ivaimpsinieps">
            <input type="hidden" name="impsinieps" id="impsinieps">
            <input type="hidden" name="ventatotal" id="ventatotal" onkeydown="return decimales(this, event)" readonly>

            <input type="hidden" name="formatofecha" id="formatofecha">
            <input type="hidden" name="idcliente" id="idcliente" value="<?php echo $_GET["cliente"]; ?>">
            <input type="hidden" name="formfecha" id="formfecha">
            <!-- Inputs para guardar la fecha por partes-->
            <input type="hidden" name="day" id="day">
            <input type="hidden" name="month" id="month">
            <input type="hidden" name="year" id="year">
            <div class="row">
              <div class="col-md-2 offset-md-10">
                <input class="btn btn-sm btn-primary" id="validate" name="validate" type="submit" onClick="agregarFecha();" value="Guardar">
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
    agregarFecha();
    calcularPrecio();
    calcularCosto();
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
      window.location.href = 'index.php?action=creditosgasolina/capturar_otorgado_cliente.php&cliente='+idCliente;
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
    if (document.getElementById('mag').checked){
      document.getElementById('pre').value = document.getElementById('pre_magna').value;
    }
    if (document.getElementById('premi').checked){
      document.getElementById('pre').value = document.getElementById('pre_premium').value;
    }
    if (document.getElementById('die').checked){
      document.getElementById('pre').value = document.getElementById('pre_diesel').value;
    }

    var dia = document.getElementById('diaini').value;
    var mes = document.getElementById('mesini').value;
    var ano = document.getElementById('anioini').value;
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

  function cambiarTipoProducto(){
    //Se deshabilitan las opciones de aceite si no es el producto seleccionado
    if (document.getElementById('ace').checked){
      $("#aceite").prop("disabled",false);
      document.getElementById('tipo').value = document.getElementById('ace').value;
    }
    else{
      $("#aceite").val(0);
      $("#aceite").prop("disabled",true);

      if (document.getElementById('mag').checked){
        document.getElementById('tipo').value = document.getElementById('mag').value;
      }
      if (document.getElementById('premi').checked){
        console.log(document.getElementById('premi').value);
        document.getElementById('tipo').value = document.getElementById('premi').value;
      }
      if (document.getElementById('die').checked){
        document.getElementById('tipo').value = document.getElementById('die').value;
      }
    }
  }

  function calcularPrecio(){
    var precio = document.getElementById('pre').value;
    var litros = document.getElementById('lit').value;
    document.getElementById('imp').value = parseFloat(eval(precio*litros)).toFixed(2);
    calcularCosto();
   }

   function calcularCosto(){
    var magnaa= document.getElementById('ieps_magna').value;
    var premiumm= document.getElementById('ieps_premium').value;
    var diesell= document.getElementById('ieps_diesel').value;
    
    var litros = document.getElementById('lit').value;

    if (document.getElementById('mag').checked){
        document.getElementById('pre').value= document.getElementById('pre_magna').value;
        document.getElementById('ieps').value = parseFloat(eval(litros * magnaa)).toFixed(2);
    }
    if (document.getElementById('premi').checked){
        document.getElementById('pre').value= document.getElementById('pre_premium').value;
        document.getElementById('ieps').value = parseFloat(eval(litros * premiumm)).toFixed(2);
    }
    if (document.getElementById('die').checked){
        document.getElementById('pre').value= document.getElementById('pre_diesel').value;
        document.getElementById('ieps').value = parseFloat(eval(litros * diesell)).toFixed(2);
    }
    if (document.getElementById('ace').checked){
        document.getElementById('ieps').value = 0.00;
    }

    var ventacontado = document.getElementById('imp').value;
    var ieps = document.getElementById('ieps').value;

    var subtotal = parseFloat(eval(ventacontado - ieps)).toFixed(2);
    document.getElementById('impsinieps').value = subtotal;

    var iva1 = parseFloat(eval(subtotal / 1.16)).toFixed(2);
    document.getElementById('ivaimpsinieps').value = iva1;

    var iva2 = parseFloat(eval(iva1 * 0.16)).toFixed(2);
    document.getElementById('iva').value = iva2;

    var subtotal1 = parseFloat(eval(eval(parseFloat(iva2) + parseFloat(ventacontado))+parseFloat(ieps))).toFixed(2);
    document.getElementById('ventatotal').value = subtotal1;
  }

  function soloNumerosDecimales3(e, valInicial, nDecimal) {
    var obj = e.srcElement || e.target;
    var tecla_codigo = (document.all) ? e.keyCode : e.which;
    var tecla_valor = String.fromCharCode(tecla_codigo);
    var patron2 = /[\d.]/;
    var control = (tecla_codigo === 46 && (/[.]/).test(obj.value)) ? false : true;
    var existePto = (/[.]/).test(obj.value);

    if (document.getElementById('mag').checked){
      nEntero=2;
    }

    if (document.getElementById('premi').checked){
      nEntero=2;
    }

    if (document.getElementById('die').checked){
        nEntero=2;
    }
    
    if (document.getElementById('ace').checked){
        nEntero=3;
    }

    //el tab
    if (tecla_codigo === 8) return true;

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
</script>