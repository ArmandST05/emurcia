<?php
$modelZona = new ModelZona();
if ($_SESSION["tipoUsuario"] == "su") {
  $zonas = $modelZona->obtenerZonasGas();

  $zonaId = (!empty($_GET["zonaId"])) ? $_GET["zonaId"] : $zonas[0]["idzona"];
} else {
  $zonaId = $_SESSION['zonaId'];
}

$day = date("d");
$mes = date("m");
$anio = date("Y");
$fechadia = $anio . "-" . $mes . "-" . $day;

$month = ["01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];
$mess = date("m");
$anioo = date("Y");
$day = date("d");
$mes = date("m");
$anio = date("Y");
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="index.php?action=comprasgas/index.php">Compras Gas</a> /
    <a href="#">Nueva</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Nueva compra Gas
    <?php if ($_SESSION["tipoUsuario"] == "su") : ?>
      <div class="form-group">
        <select class="form-control form-control-sm" id="zonaId" onchange="seleccionZona()" required>
          <?php foreach ($zonas as $dataZona) : ?>
            <option value="<?php echo $dataZona['idzona'] ?>" <?php echo ($zonaId == $dataZona['idzona']) ? "selected" : "" ?>>
              <?php echo strtoupper($dataZona["nombre"]) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    <?php endif; ?>
  </h1>
</div>

<!-- Content Row -->
<div class="row">

  <!-- Nuevo -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <div class="card-body">
        <form action="../controller/Compras/InsertarCompraGas.php" method="POST">
          <div class="row">
            <table border="0">
              <tr>
                <td>Fecha Embarque: </td>
                <td>
                  <select name="diaini" id="diaini">
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

          <select name="mesini" id="mesini">
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
      <select name="anioini" id="anioini">
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
      <tr>
        <td>No. Factura: </td>
        <td><input class="form-control form-control-sm" type="text" name="nocompra" id="nocompra" required></td>
      </tr>
      <tr>
        <td id="nc">Transporte: </td>
        <td><input class="form-control form-control-sm" type="text" name="nombrep" id="nombrep" required></td>
      </tr>
      <tr>
        <td id='pz'>Fecha Descarga: </td>
        <td><select name="diainie" id="diainie">
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
          /
          <select name="mesinie" id="mesinie">
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
          /
          <select name="anioinie" id="anioinie">
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
        </td>
      </tr>
      <tr>
        <td id='pz'>Origen: </td>
        <td><input class="form-control form-control-sm" type="text" name="origen" id="origen" required></td>
      </tr>
      <tr>
        <td>Destino: </td>
        <td><input class="form-control form-control-sm" type="text" name="destino" id="destino" required></td>
      </tr>
      <tr>
        <td>Kilogramos: </td>
        <td><input class="form-control form-control-sm" type="text" name="kilos" id="kilos" required onChange="calcularLitros();"></td>
      </tr>
      <td>Densidad</td>
      <td><input class="form-control form-control-sm" type="text" name="densidad" id="densidad" onChange="calcularLitros();" required></td>
      </tr>
      <td>Litros</td>
      <td><input class="form-control form-control-sm" type="text" name="litros" id="litros" required readonly></td>
      </tr>
      <tr>
        <td>Empresa: </td>
        <td>
          <select class="form-control form-control-sm" name="zona">
            <option value='Teocaltiche'>Teocaltiche (ALFA GAS PTO)</option>
            <option value='VillaHidalgo'>Villa Hidalgo (ALFA GAS PV)</option>
            <option value='Jerez'>Jerez (LUX)</option>
            <option value='Villacos'>VillCos (AURE)</option>
            <option value='Tlaltenango'>Tlaltenango(CAÑON)</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>Descargada en: </td>
        <td><select class="form-control form-control-sm" name="zonadescarga">
            <option value='Teocaltiche'>Teocaltiche</option>
            <option value='VillaHidalgo'>Villa Hidalgo</option>
            <option value='Jerez'>Jerez</option>
            <option value='Villacos'>Villacos</option>
            <option value='Tlaltenango'>Tlaltenango</option>
          </select></td>
      </tr>
      </table>
    </div>
    <div class="row">
      <div class="col-md-1 offset-md-11">
        <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
        <input type="hidden" name="zona" value="<?php echo $zonaId ?>" required>
      </div>
    </div>
  </div>
</div>
</div>
</div>

<script type="text/JavaScript">
  $(document).ready(function() {
  });

  function calcularLitros(){
    var kilos = $("#kilos").val();
    var densidad = $("#densidad").val();
    litros = 0;
    if(kilos != 0 && densidad != 0) litros = parseFloat(kilos/densidad).toFixed(2);

    $("#litros").val(litros);
  }

  function SoloNumerosDecimales3(e, valInicial, nDecimal) {
    var obj = e.srcElement || e.target;
    var tecla_codigo = (document.all) ? e.keyCode : e.which;
    var tecla_valor = String.fromCharCode(tecla_codigo);
    var patron2 = /[\d.]/;
    var control = (tecla_codigo === 46 && (/[.]/).test(obj.value)) ? false : true;
    var existePto = (/[.]/).test(obj.value);

    nEntero=2;

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

  function seleccionZona(){
      window.location.href = "index.php?action=comprasgas/nuevo.php&zonaId="+ $("#zonaId").val();
    }
</script>