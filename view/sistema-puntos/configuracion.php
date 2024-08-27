<?php
$modelZona = new ModelZona();
$modelProducto = new ModelProducto();
$meses = ["1" => "Enero", "2" => "Febrero", "3" => "Marzo", "4" => "Abril", "5" => "Mayo", "6" => "Junio", "7" => "Julio", "8" => "Agosto", "9" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];
$mes = date("n");
$anio = date("Y");
$zonas = $modelZona->obtenerZonasTodas();
$zonaId = (isset($_GET["zonaId"])) ? $_GET["zonaId"] : "";
$zonaData = $modelZona->obtenerZonaId($zonaId);

$mesNombre = $meses[$mes];

$productosData = $modelProducto->productosPorZona($zonaId);
if (!empty($productosData)) {
  $productosArray = array_chunk($productosData, 3);
}
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Sistema Puntos /</a>
    <a href="#">Configuración</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Configuración de App Móvil</h1>
</div>
<!-- Content Row -->
<div class="row">
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">APLICACIÓN VENDEDOR</h6>
      </div>
      <!-- Card Body -->
      <div class="card-body">
          <div class="row">
            <div class="col-lg-6">
              <a class="btn btn-primary" href="../EMURCIA-Vendedor.apk">DESCARGAR APP ANDROID<br><i class="fas fa-download"></i></a>
            </div>
            <div class="col-lg-6">
              <a class="btn btn-warning" href="../Manual de Usuario Vendedor Grupo Emurcia.pdf">DESCARGAR MANUAL USO<br><i class="fas fa-download"></i> <i class="fas fa-file-pdf"></i></a>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>
<!--
<div class="row">
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">APLICACIÓN CLIENTE</h6>
      </div>
      <div class="card-body">
          <div class="row">
            <div class="col-lg-6">
              <a class="btn btn-primary" href="../../EMURCIA-Cliente.apk">DESCARGAR APP ANDROID<br><i class="fas fa-download"></i></a>
            </div>
            <div class="col-lg-6">
            <a class="btn btn-warning" href="../../Manual de Usuario Vendedor Grupo Emurcia.pdf">DESCARGAR MANUAL USO<br><i class="fas fa-download"></i> <i class="fas fa-file-pdf"></i></a>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>-->
<script type="text/JavaScript">
  $(document).ready(function(){
    $("#zonaId").select2({});
  });

</script>