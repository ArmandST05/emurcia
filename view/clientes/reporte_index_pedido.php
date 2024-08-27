<?php
$modelZona = new ModelZona();
$modelClientePedido = new ModelClientePedido;
if ($_SESSION["tipoUsuario"] == "su") {
  $zonaId = $_GET["zona"];
  $zonas = $modelZona->listaTodas();
} else {
  $zona = $_SESSION["zona"];
  $zonaId = $_SESSION["zonaId"];
}

$clientes = $modelClientePedido->listaPorZona($zonaId);
?>
<!-- Content Row -->
<div class="row" id="tableDiv">
  <!-- Nuevo Pedido -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Header -->
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Lista Clientes
      </div>
      <!-- Card Header -->
      <!-- Card Body -->
      <div class="card-body">
        <div class="row" align="right">
          <div class="lds-ring">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
          </div>
        </div>
        <?php if (!empty($zonaId)) : ?>
          <table id="listaTabla" class="table table-responsive table-bordered table-sm listaTabla">
            <thead>
              <tr>
                <th>Id</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Colonia</th>
                <th>Teléfono</th>
                <th>Zona</th>
                <!--
                <th>Último Pedido</th>
                <th>Periodicidad Sistema</th>
                <th>Próxima Entrega Sistema</th>
                <th>Periodicidad Usuario</th>
                <th>Próxima Entrega Usuario</th>
                <th>Producto</th>
                -->
              </tr>
            </thead>
            <tbody>
              <?php foreach ($clientes as $cliente) : ?>
                <tr>
                  <td><?php echo $cliente['idclientepedido'] ?></td>
                  <td><?php echo $cliente['nombre'] ?></td>
                  <td><?php echo $cliente['direccion'] ?></td>
                  <td><?php echo $cliente['colonia'] ?></td>
                  <td><?php echo $cliente['telefono'] ?></td>
                  <td><?php echo $cliente['ciudad'] ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <!-- Card Body -->
</div>

<script>
  $(document).ready(function() {
    $("#tableDiv").hide();
    var zonaId = "<?php echo $zonaId ?>";
    $("#listaTabla").btechco_excelexport({
      containerid: "listaTabla",
      datatype: $datatype.Table,
      filename: 'Clientes-Pedidos-Zona'
    });
    window.close();
  });
</script>