<?php
$modelRuta = new ModelRuta();
$modelAutoconsumo = new ModelAutoconsumo();
$modelCompania = new ModelCompania();
$companias = $modelCompania->listaTodas();
if ($_SESSION["tipoUsuario"] == "u") $rutas = $modelRuta->listaPorZonaEstatus($_SESSION['zonaId'],1);

$day = date("d");
$mes = date("m");
$anio = date("Y");
$dayf = date("d");
$mesf = date("m");
$aniof = date("Y");

?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="../view/index.php?action=autoconsumos/index.php">Autoconsumos</a> /
    <a href="#">Nuevo</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Nuevo Autoconsumo</h1>
</div>
  
<!-- Content Row -->
<div class="row">
  <!-- Nuevo Pedido -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Body -->
      <div class="card-body">
        <form action="../controller/Autoconsumos/InsertarAutoconsumo.php" method="POST">
          <div class="row">
            <div class="col-md-4">
               
    <label for="comprobante">Subir Comprobante de Autoconsumo:</label>
    <input type="file" name="comprobante" id="comprobante" required>
    
    
            </div>
          </div>
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label>Ruta</label>
                <select class="form-control form-control-sm" id="ruta" name="ruta" required onchange="obtenerKmInicial()">
                  <option selected disabled>Seleccione una</option>
                  <?php
                  foreach ($rutas as $data) {
                    echo "<option value='" . $data["idruta"] . "'";
                    echo ">" . $data["clave_ruta"] . "</option>";
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label>Inicio</label>
                <div class="row">
                  <div class="col-md-3">
                    <select class="form-control form-control-sm" name="diaIni" id="diaIni" onChange="calcularfecha(); ">
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
                  <div class="col-md-5">
                    <select class="form-control form-control-sm" name="mesIni" id="mesIni" onChange="calcularfecha();">
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
                  <div class="col-md-4">
                    <select class="form-control form-control-sm" name="anioIni" id="anioIni" onChange="calcularfecha();">
                      <?php
                      for ($k = $anio; $k >= 2010; $k--) {
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
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label>Fin</label>
                <div class="row">
                  <div class="col-md-3">
                    <select class="form-control form-control-sm" name="diaFin" id="diaFin" onChange="calcularfechaf(); ">
                      <?php
                      for ($i = 1; $i <= 31; $i++) {
                        echo "<option value=" . $i;
                        if ($dayf == $i) {
                          echo " selected='selected'";
                        }
                        echo ">" . $i . "</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <div class="col-md-5">
                    <select class="form-control form-control-sm" name="mesFin" id="mesFin" onChange="calcularfechaf();">
                      <?php
                      $meses = ["1" => "Enero", "2" => "Febrero", "3" => "Marzo", "4" => "Abril", "5" => "Mayo", "6" => "Junio", "7" => "Julio", "8" => "Agosto", "9" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];
                      for ($j = 1; $j <= 12; $j++) {
                        echo "<option value=" . $j;
                        if ($mesf == $j) {
                          echo " selected='selected'";
                        }
                        echo ">" . $meses[$j] . "</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <select class="form-control form-control-sm" name="anioFin" id="anioFin" onChange="calcularfechaf();">
                      <?php
                      for ($k = $anio; $k >= 2010; $k--) {
                        echo "<option value=" . $k;
                        if ($aniof == $k) {
                          echo " selected='selected'";
                        }
                        echo ">" . $k . "</option>";
                      }
                      ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label>Combustible</label>
                <select class="form-control form-control-sm" id="combustible" name="combustible" onchange="obtenerKmInicial()">
                  <option value="Diesel" selected>Diesel</option>
                  <option value="Gasolina">Gasolina</option>
                  <option value="Gas LP">Gas LP</option>
                </select>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label>Litros</label>
                <input class="form-control form-control-sm" type="text" name="litros" id="litros" onChange="calcular();" onkeydown="return decimales(this, event)" required>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label>Costo/litro</label>
                <input class="form-control form-control-sm" type="text" name="costo_litro" id="costo_litro" onChange="calcular();" onkeydown="return decimales(this, event)" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label>Km inicio:</label>
                <input class="form-control form-control-sm" type="text" name="kmi" id="kmi" onChange="calcular();" onkeydown="return decimales(this, event)" required>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label>Km final:</label>
                <input class="form-control form-control-sm" type="text" name="kmf" id="kmf" onChange="calcular();" onkeydown="return decimales(this, event)" required>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label>Costo total:</label>
                <input class="form-control form-control-sm" type="text" name="total" id="total" readonly onkeydown="return decimales(this, event)">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col offset-md-8">
              <div class="form-group">
                <label>Rendimiento:</label>
                <input class="form-control form-control-sm" type="text" name="rendi" id="rendi" readonly onkeydown="return decimales(this, event)">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col offset-md-11">
              <div class="form-group">
                <input class="btn btn-sm btn-primary" type="submit" value="Guardar">
              </div>
            </div>
          </div>
          <input type="hidden" name="fecha" id="fecha" />
          <input type="hidden" name="fechaf" id="fechaf" />
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {});

  var objeto2;

  function decimales(objeto, e) {
    var keynum
    var keychar
    var numcheck
    if (window.event) {
      /*/ IE*/
      keynum = e.keyCode
    } else if (e.which) {
      /*/ Netscape/Firefox/Opera/*/
      keynum = e.which
    }
    if ((keynum >= 35 && keynum <= 37) || keynum == 8 || keynum == 9 || keynum == 46 || keynum == 39) {
      return true;
    }
    if (keynum == 190 || keynum == 110 || (keynum >= 95 && keynum <= 105) || (keynum >= 48 && keynum <= 57)) {
      posicion = objeto.value.indexOf('.');
      if (posicion == -1) {
        return true;
      } else {
        if (!(keynum == 190 || keynum == 110)) {
          objeto2 = objeto;
          t = setTimeout('dosDecimales()', 150);
          return true;
        } else {
          objeto2 = null;
          return false;
        }
      }
    } else {
      return false;
    }
  }

  function dosDecimales() {
    var objeto = objeto2;
    var posicion = objeto.value.indexOf('.');
    var decimal = 2;
    if (objeto.value.length - posicion < decimal) {
      objeto.value = objeto.value.substr(0, objeto.value.length - 1);
    } else {
      objeto.value = objeto.value.substr(0, posicion + decimal + 1);
    }
    return;
  }

  function enteros(objeto, e) {
    var keynum
    var keychar
    var numcheck
    if (window.event) {
      /*/ IE*/
      keynum = e.keyCode
    } else if (e.which) {
      /*/ Netscape/Firefox/Opera/*/
      keynum = e.which
    }
    if ((keynum >= 35 && keynum <= 37) || keynum == 8 || keynum == 9 || keynum == 46 || keynum == 39) {
      return true;
    }
    if ((keynum >= 95 && keynum <= 105) || (keynum >= 48 && keynum <= 57)) {
      return true;
    } else {
      return false;
    }
  }

  function calcular() {
    var costo_litro = document.getElementById('costo_litro').value;
    var kmi =  parseFloat(document.getElementById('kmi').value);
    var kmf =  parseFloat(document.getElementById('kmf').value);
    var litros =  parseFloat(document.getElementById('litros').value);

    document.getElementById('total').value = parseFloat(eval(costo_litro * litros)).toFixed(2);
    document.getElementById('rendi').value = parseFloat(eval((kmf - kmi) / litros)).toFixed(2);
  }

  function obtenerKmInicial(){
    let rutaId = parseFloat($("#ruta").val());
    let productoNombre = $("#combustible").val();
  
    $.ajax({
      data: {
        rutaId: rutaId,
        productoNombre: productoNombre
      },
      type: "GET",
      url: '../controller/Rutas/ObtenerUltimoKmRutaProducto.php',
      dataType: "json",
      success: function(data) {
        $("#kmi").val(parseFloat(data));
      },
      error: function(data) {
        alertify.error('Ha ocurrido un error al obtener el último kilometraje de la ruta/unidad');
      }
    });
  }
</script>