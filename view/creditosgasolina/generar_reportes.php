<?php
$modelZona = new ModelZona();
$zona = $_SESSION["zonaId"];
$anio = date("Y");
$mes = date("m");
$dia = date("d");
$zonas = $modelZona->obtenerZonasGasolina();
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="index.php?action=creditosgasolina/index.php">Créditos Gasolina</a> /
    <a href="#">Reportes</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Reportes</h1>
</div>
<!-- Content Row -->
<div class="row">
  <div class="col-xl-4 col-md-6 mb-4">
    <div class="card border-left-warning shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-6">
            <!--  <img src="../images/gallery/rel_ini.jpg"/> -->
            <div class="font-weight-bold text-warning text-uppercase mb-1">Generar reporte de la relación inicial al mes</div>
            <form action="index.php?action=reportes/relacion_inicial_mes_gasolina.php" method="post">
              <div class="row">
                <div class="col-md-6">
                  <select class="form-control form-control-sm" name="mes">
                    <?php
                    $meses = ["1" => "Enero", "2" => "Febrero", "3" => "Marzo", "4" => "Abril", "5" => "Mayo", "6" => "Junio", "7" => "Julio", "8" => "Agosto", "9" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];
                    for ($j = 01; $j <= 12; $j++) {
                      echo "<option value='" . $j . "'";
                      if (strcmp($mes, $j) == 0) {
                        echo "selected='selected'";
                      }
                      echo ">" . $meses[$j] . "</option>";
                    }
                    ?>
                  </select>
                </div>
                <div class="col-md-6">
                  <select class="form-control form-control-sm" name="anio">
                    <?php
                    for ($k = $anio; $k >= 2000; $k--) {
                      echo "<option value=" . $k . ">" . $k . "</option>";
                    }
                    ?>
                  </select>
                </div>
              </div>
              <?php if ($_SESSION['tipoUsuario'] == "su" || $_SESSION["tipoUsuario"] == "uc") : ?>
                <div class="row">
                  <div class="col-md-12">
                    <select class="form-control form-control-sm" name="zona">
                      <?php foreach ($zonas as $data) : ?>
                        <option value="<?php echo $data['idzona']; ?>">
                          <?php echo $data['nombre']; ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              <?php endif; ?>
              <div class="row">
                <div class="col-md-3 offset-md-9">
                  <input class="btn btn-sm btn-primary" type="submit" value="Generar">
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-4 col-md-6 mb-4">
    <div class="card border-left-warning shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-6">
            <!--  <img src="../../images/gallery/ganancias_dia.jpg" /> -->
            <div class="font-weight-bold text-warning text-uppercase mb-1">Relación de créditos en el día</div>
            <form action="index.php?action=reportes/relacion_dia_gasolina.php" method="POST">
              <div class="row">
                <div class="col-md-4">
                  <select class="form-control form-control-sm" name="diaini">
                    <?php for ($i = 01; $i <= 31; $i++) : ?>
                      <option value="<?php echo $i ?>" <?php echo ($dia == $i) ? "selected" : "" ?>><?php echo $i ?></option>
                    <?php endfor; ?>
                  </select>
                </div>
                <div class="col-md-4">
                  <select class="form-control form-control-sm" name="mesini">
                    <?php
                    $meses = ["1" => "Enero", "2" => "Febrero", "3" => "Marzo", "4" => "Abril", "5" => "Mayo", "6" => "Junio", "7" => "Julio", "8" => "Agosto", "9" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];
                    for ($j = 1; $j <= 12; $j++) : ?>
                      <option value="<?php echo $j ?>" <?php echo (strcmp($mes, $j) == 0) ? "selected" : ""; ?>><?php echo $meses[$j] ?>
                      </option>
                    <?php endfor; ?>
                  </select>
                </div>
                <div class="col-md-4">
                  <select class="form-control form-control-sm" name="anioini">
                    <?php
                    for ($k = $anio; $k >= 2000; $k--) {
                      echo "<option value=" . $k . ">" . $k . "</option>";
                    }
                    ?>
                  </select>
                </div>
              </div>
              <?php if ($_SESSION['tipoUsuario'] == "su" || $_SESSION["tipoUsuario"] == "uc") : ?>
                <div class="row">
                  <div class="col-md-12">
                    <select class="form-control form-control-sm" name="zona">
                      <?php foreach ($zonas as $data) : ?>
                        <option value="<?php echo $data['idzona']; ?>">
                          <?php echo $data['nombre']; ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              <?php endif; ?>
              <div class="row">
                <input class="btn btn-sm btn-primary offset-md-10" type="submit" value="Generar">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-4 col-md-6 mb-4">
    <div class="card border-left-warning shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-6">
            <!--  <img src="../../images/gallery/rel_fin.jpg" /> -->
            <div class="font-weight-bold text-warning text-uppercase mb-1">Generar reporte de la relación final del mes actual</div>
            <form action="index.php?action=reportes/relacion_fin_mes_gasolina.php" method="POST">
              <?php if ($_SESSION['tipoUsuario'] == "su" || $_SESSION["tipoUsuario"] == "uc") : ?>
                <div class="row">
                  <div class="col-md-12">
                    <select class="form-control form-control-sm" name="zona">
                      <?php foreach ($zonas as $data) : ?>
                        <option value="<?php echo $data['idzona']; ?>">
                          <?php echo $data['nombre']; ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              <?php endif; ?>
              <input class="btn btn-sm btn-primary offset-md-10" type="submit" value="Generar">
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-xl-4 col-md-6 mb-4">
    <div class="card border-left-warning shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-12">
            <!--  <img src="../../images/gallery/saldo_clientes.jpg" /> -->
            <div class="font-weight-bold text-warning text-uppercase mb-1">Relación de saldos de clientes</div>
            <label>Seleccione un cliente:</label>
            <form action="index.php?action=reportes/relacion_saldos_cliente_gasolina.php" method="POST">
              <div class="row">
                <select class="form-control form-control-sm" name="cliente" id="clienteSaldos">
                </select>
                <input type='hidden' name='dia' id='dia' />
                <input type='hidden' name='mes' id='mes' />
                <input type='hidden' name='zona' id='zona' />
                <input class="btn btn-sm btn-primary offset-md-10" type="submit" value="Generar">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-4 col-md-6 mb-4">
    <div class="card border-left-warning shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-6">
            <!--  <img src="../../images/gallery/rel_fin.jpg" /> -->
            <div class="font-weight-bold text-warning text-uppercase mb-1">Relación de créditos otorgados por cliente</div>
            <label>Seleccione un cliente:</label>
            <form action="index.php?action=reportes/relacion_inicial_mes_cliente_gasolina.php" method="POST">
              <div class="row">
                <select class="form-control form-control-sm" name="cliente" id="clienteCreditosOtorgados">
                </select>
                <input type='hidden' name='dia' id='dia' />
                <input type='hidden' name='mes' id='mes' />
                <input type='hidden' name='zona' id='zona' />
              </div>
              <input class="btn btn-sm btn-primary offset-md-10" type="submit" value="Generar">
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/JavaScript">
  $('#clienteSaldos').select2({
    placeholder: "Escribe el nombre de cliente/comercial",
    minimumInputLength: 4,
    ajax: {
      url: '../controller/Clientes/BuscarClientesGasolinaNombre.php',
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

  $('#clienteCreditosOtorgados').select2({
    placeholder: "Escribe el nombre de cliente/comercial",
    minimumInputLength: 4,
    ajax: {
      url: '../controller/Clientes/BuscarClientesGasolinaNombre.php',
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
</script>