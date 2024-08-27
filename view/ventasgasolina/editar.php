<?php
$modelVentaGasolina = new ModelVentaGasolina();
$zonaId = $_SESSION['zonaId'];
$ventaId = $_GET['id'];

$venta = $modelVentaGasolina->obtenerVentaPorId($ventaId);
$venta = reset($venta);
$fechaActual = date("Y-m-d");

if (!$venta) {
  echo "<script> 
          alert('No puedes editar esta venta');
          window.location.href = 'index.php?action=ventas/index.php';
        </script>";
}
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="index.php?action=ventasgasolina/index.php">Ventas</a> /
    <a href="#">Gasolina</a> /
    <a href="#">Editar</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Editar venta</h1>
</div>

<!-- Content Row -->
<div class="row">

  <!-- Nuevo Pedido -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <div class="card-body">
        <form action="../controller/VentasGasolina/Actualizar.php" method="POST" id="formEditarVenta">
          <?php if ($_SESSION["tipoUsuario"] == "su") : ?>
            <div class="row">
              <div class="alert alert-warning col-md-12" role="alert">
                Sólo se permite editar la fecha de la venta si no se ha generado una venta posterior en esa ruta, para evitar irregularidades.
              </div>
            </div>
          <?php endif; ?>
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Fecha</label>
                <input type="date" class="form-control form-control-sm" name="fechaVta" id="fechaVta" max="<?php echo date("Y-m-d"); ?>" value="<?php echo $venta["fecha"] ?>" onchange="obtenerInventarioRuta()">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Almacén</label>
                <input type="text" class="form-control form-control-sm" name="rutaVta" id="rutaVta" value="<?php echo $venta["ruta_nombre"] ?>" data-tipo="<?php echo $venta['tipo_ruta_id'] ?>" data-id="<?php echo $venta['ruta_id'] ?>" readonly>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Producto</label>
                <input type="text" class="form-control form-control-sm" name="productoVta" id="productoVta" value="<?php echo $venta["producto_nombre"] ?>" data-id="<?php echo $venta['producto_id'] ?>" readonly required>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Inventario</label>
                <div class="input-group input-group-sm mb-3">
                  <input type="number" class="form-control form-control-sm" name="inventarioVta" id="inventarioVta" min="0" step=".01" value="0" readonly>
                  <div class="input-group-append">
                    <span class="input-group-text">Lts</span>
                  </div>
                </div>

              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Pruebas</label>
                <div class="input-group input-group-sm mb-3">
                  <input type="number" class="form-control form-control-sm" name="pruebasVta" id="pruebasVta" min="0" step=".01" value="<?php echo $venta["pruebas"] ?>">
                  <div class="input-group-append">
                    <span class="input-group-text">Lts</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Cantidad Total (Lts)</label>
                <div class="input-group input-group-sm mb-3">
                  <input type="number" class="form-control form-control-sm rounded-right" name="cantidadVta" id="cantidadVta" min="0" step=".01" value="<?php echo $venta["cantidad"]; ?>" required onchange="calcularTotalVta()">
                  <div class="input-group-append">
                    <span class="input-group-text">Lts</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Precio</label>
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                  </div>
                  <input type="number" class="form-control form-control-sm" name="precioVta" id="precioVta" min="0" step=".01" value="<?php echo $venta['precio']; ?>" required onchange="calcularTotalVta();" <?php echo (array_search("ventas.precio-producto.editar", $_SESSION["permisos"]) !== false) ? "" : "readonly" ?>>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Venta Contado</label>
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                  </div>
                  <input type="number" class="form-control form-control-sm" name="totalVtaContado" id="totalVtaContado" min="0" step=".01" value="<?php echo $venta['cantidad_venta_contado'] ?>" readonly>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3 offset-md-6">
              <div class="form-group">
                <label>Cantidad Crédito (Lts)</label>
                <div class="input-group input-group-sm mb-3">
                  <input type="number" class="form-control form-control-sm" name="cantidadVtaCredito" id="cantidadVtaCredito" min="0" step=".01" value="<?php echo $venta["cantidad_venta_credito"] ?>" onchange="calcularTotalVtaCredito()">
                  <div class="input-group-append">
                    <span class="input-group-text">Lts</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Venta Crédito</label>
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                  </div>
                  <input type="number" class="form-control form-control-sm" name="totalVtaCredito" id="totalVtaCredito" min="0" step=".01" value="<?php echo $venta["total_venta_credito"] ?>" onchange="calcularTotalVtaContado()" readonly>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3 offset-md-9">
              <div class="form-group">
                <label>Total Venta</label>
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                  </div>
                  <input type="number" class="form-control form-control-sm" name="totalVta" id="totalVta" min="0" step=".01" value="0" readonly>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-1 offset-md-11">
              <input type="hidden" name="rutaId" value="<?php echo $venta['ruta_id'] ?>">
              <input type="hidden" name="productoId" value="<?php echo $venta['producto_id'] ?>">
              <input type="hidden" name="detalleVtaId" value="<?php echo $venta['detalle_venta_id'] ?>">
              <input type="hidden" name="ventaId" value="<?php echo $ventaId ?>">
              <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/JavaScript">
  $(document).ready(function(){
      var zonaId = "<?php echo $zonaId ?>";
      let rutaId = $("#rutaVta").data('id');
      let tipoRuta = $("#rutaVta").data('tipo');
      $('#precioVta').prop('readonly', true);
      obtenerInventarioRuta();
      obtenerPermisosRuta(rutaId);
      calcularTotalVta();
    });

    //Validar formulario
    $("#formEditarVenta" ).submit(function( event ) {
      let ventaId = "<?php echo $ventaId ?>";
      let rutaId = "<?php echo $venta['ruta_id'] ?>";
      let cantidadVta = parseFloat($("#cantidadVta").val());
      let precioVta = parseFloat($("#precioVta").val());
      let inventarioVta = parseFloat($("#inventarioVta").val());   
      let pruebasVta = parseFloat($("#pruebasVta").val());       
      let productoId = parseFloat($("#productoVta").data('id'));
      let fechaNuevaVta = $("#fechaVta").val();

      if(new Date(fechaNuevaVta) > new Date()){
        event.preventDefault();
        alertify.error("No puedes registrar ventas en fechas posteriores");
      }
      else if(cantidadVta <= 0){
        event.preventDefault();
        alertify.error("Ingresa una cantidad válida");
      }
      else if(precioVta <= 0){
        event.preventDefault();
        alertify.error("Ingresa un precio válido");
      }
      else if(inventarioVta < 0.01 || (cantidadVta+pruebasVta) > inventarioVta ){
        event.preventDefault();
        alertify.error("No tienes inventario suficiente");
      }
  });

    function calcularTotalVta(){
      var totalVta = parseFloat($("#cantidadVta").val()) * parseFloat($("#precioVta").val());
      $("#totalVta").val(parseFloat(totalVta).toFixed(2));
      calcularTotalVtaContado();
    }

    function calcularTotalVtaCredito(){
      let cantidadVtaCredito = parseFloat($("#cantidadVtaCredito").val());
      let precioVta = parseFloat($("#precioVta").val());
      if(!cantidadVtaCredito) cantidadVtaCredito = 0;
      let totalVtaCredito = cantidadVtaCredito * precioVta;
      $("#totalVtaCredito").val(parseFloat(totalVtaCredito).toFixed(2));
      calcularTotalVtaContado();
    }

    function calcularTotalVtaContado(){
      let totalVtaCredito = parseFloat($("#totalVtaCredito").val());
      let totalVta = parseFloat($("#totalVta").val());

      if(!totalVtaCredito) totalVtaCredito = 0;
      if(!totalVta) totalVta = 0;

      let totalVtaContado = totalVta - totalVtaCredito;
      $("#totalVtaContado").val(parseFloat(totalVtaContado).toFixed(2));
    }

    function obtenerPermisosRuta(rutaId){
      //Activa los campos de acuerdo a los permisos asignados
      $.ajax({
        data: { rutaId : rutaId },
        type: "GET",
        url: '../controller/Permisos/ObtenerPorRuta.php', 
        dataType: "json",
        success: function(data){
          let permisos = data;  
          if(permisos.includes('ventas.lectura-inicial.editar')) $("#lecturaInicialVta").prop('readonly',false);
          if(permisos.includes('ventas.precio-producto.editar')) $("#precioVta").prop('readonly',false);
        },
        error: function(data) {
          alertify.error('Ha ocurrido un error al cargar los permisos de la ruta.');
        }
      });
    }

    function obtenerInventarioRuta(){
      let rutaId = $("#rutaVta").data('id');
      let productoId = $("#productoVta").data('id');
      let zonaId = "<?php echo $zonaId ?>";
      let fechaVta = $("#fechaVta").val();

      //Obtener inventario del producto
      $.ajax({
        data: { rutaId : rutaId, zonaId: zonaId, fechaVta:fechaVta },
        type: "GET",
        url: '../controller/Inventario/ObtenerInventarioGasolinaRuta.php', 
        dataType: "json",
        success: function(data){
          let inventario = parseFloat(data) + parseFloat($("#cantidadVta").val()) + parseFloat($("#pruebasVta").val());

          $("#inventarioVta").val(parseFloat(inventario).toFixed(2));  
        },
        error: function(data) {
          alertify.error('Ha ocurrido un error al cargar el inventario');
        }
      });
    }
</script>