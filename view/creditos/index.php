<?php
$modelReporte = new ModelReporte();
$zona = $_SESSION["zonaId"];

if ($_SESSION["tipoUsuario"] == "u" && $_SESSION["tipoZona"] != 1) {
  echo "<script> 
          alert('Tu zona no vende GAS... Redireccionando a créditos de gasolina');
          window.location.href = 'index.php?action=creditosgasolina/index.php';
        </script>";
}
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Créditos Gas</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Créditos Gas</h1>
</div>

<!-- Content Row -->
<div class="row">
  <?php if($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "u"): ?>
  <!-- Mini Card -->
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-warning shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="font-weight-bold text-warning text-uppercase mb-1">Crédito otorgado</div>
            <a class="btn btn-sm btn-primary shadow-sm mb-0" href="index.php?action=creditos/capturar_otorgado_cliente.php">Capturar</a>
          </div>
          <div class="col-auto">
            <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
            <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-warning shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="font-weight-bold text-warning text-uppercase mb-1">Crédito recuperado</div>
            <a class="btn btn-sm btn-primary shadow-sm mb-0" href="index.php?action=creditos/buscar_recuperado_cliente.php">Capturar</a>
          </div>
          <div class="col-auto">
            <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
            <i class="fas fa-arrow-down fa-2x text-gray-300"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php endif;?>
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-warning shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="font-weight-bold text-warning text-uppercase mb-1">Movimientos de crédito</div>
            <a class="btn btn-sm btn-primary shadow-sm mb-0" href="index.php?action=creditos/consultar_creditos.php">Consultar</a>
          </div>
          <div class="col-auto">
            <i class="fas fa-exchange-alt fa-2x text-gray-300"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-warning shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="font-weight-bold text-warning text-uppercase mb-1">Reportes de crédito</div>
            <a class="btn btn-sm btn-primary shadow-sm mb-0" href="index.php?action=creditos/generar_reportes.php">Generar</a>
          </div>
          <div class="col-auto">
            <i class="far fa-file-alt fa-2x text-gray-300"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Mini Card -->
</div>
<!-- Content Row -->
<div class="row">
<?php if($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc"):?>
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-warning shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="font-weight-bold text-warning text-uppercase mb-1">Administrar créditos (Editar, Eliminar)</div>
            <a class="btn btn-sm btn-primary shadow-sm mb-0" href="index.php?action=creditos/administrar_cliente.php">Acceder</a>
          </div>
          <div class="col-auto">
            <i class="fas fa-tasks fa-2x text-gray-300"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php endif; ?>
</div>

<script type="text/JavaScript">

</script>