<?php
$modelRuta = new ModelRuta();
$zonaId = $_SESSION['zonaId'];
$rutas = $modelRuta->listaPorZonaVentaEstatus($zonaId, 1);
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="index.php?action=ventasgasolina/index.php">Ventas</a> /
    <a href="#">Gasolina</a> /
    <a href="#">Nueva</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Nueva venta</h1>
</div>

<!-- Content Row -->
<div class="row">

  <!-- Nuevo Pedido -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <div class="card-body">
        <form action="../controller/VentasGasolina/Insertar.php" method="POST" id="formNuevaVenta">
          <div class="row">
            <div class="col-md-2 offset-md-11">
              <button type="button" class="btn btn-light btn-sm" data-toggle="tooltip" data-placement="top" title="Limpiar" onclick="limpiarNuevaVta()">Limpiar <i class="fas fa-broom"></i></button>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Fecha</label>
                <input type="date" class="form-control form-control-sm" name="fechaVta" id="fechaVta" max="<?php echo date("Y-m-d"); ?>" value="<?php echo date("Y-m-d"); ?>" onchange="obtenerInventario()">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Almacén</label>
                <select class="form-control form-control-sm" name="rutaVta" id="rutaVta" required>
                  <option value="0" selected disabled>Seleccione Opción</option>
                  <?php foreach ($rutas as $ruta) : ?>
                    <option value="<?php echo $ruta['idruta'] ?>" data-tipo="<?php echo $ruta['tipo_ruta_id'] ?>">
                      <?php echo $ruta['clave_ruta'] ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <div class="invalid-tooltip">
                  Por favor selecciona una unidad válida.
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Producto</label>
                <select class="form-control form-control-sm" name="productoVta" id="productoVta">
                  <option value="0" selected disabled>Seleccione Opción</option>
                </select>
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
                  <input type="number" class="form-control" name="pruebasVta" id="pruebasVta" min="0" step=".01" value="0">
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
                  <input type="number" class="form-control" name="cantidadVta" id="cantidadVta" min="0" step=".01" value="0" required onchange="calcularTotalVta()">
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
                  <input type="number" class="form-control form-control-sm" name="precioVta" id="precioVta" min="0" step=".01" value="0" required onchange="calcularTotalVta();" <?php echo (array_search("ventas.precio-producto.editar", $_SESSION["permisos"]) !== false) ? "" : "readonly" ?>>
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
                  <input type="number" class="form-control" name="totalVtaContado" id="totalVtaContado" min="0" step=".01" value="0" readonly>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3 offset-md-6">
              <div class="form-group">
                <label>Cantidad Crédito (Lts)</label>
                <div class="input-group input-group-sm mb-3">
                  <input type="number" class="form-control form-control-sm" name="cantidadVtaCredito" id="cantidadVtaCredito" min="0" step=".01" value="0" onchange="calcularTotalVtaCredito()">
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
                  <input type="number" class="form-control" name="totalVtaCredito" id="totalVtaCredito" min="0" step=".01" value="0" readonly>
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
              <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/JavaScript">
  var rutas = '<?php echo json_encode($rutas) ?>';

    $(document).ready(function(){
      var zonaId = "<?php echo $zonaId ?>";
      $("#divLecturas").hide();
      $('#rubros-6').prop('readonly', false);
    });

    $("#rutaVta").change(function() {
      let rutaId = $("#rutaVta").val();
      let tipoRuta = $(this).find(':selected').data('tipo');
      let zonaId = "<?php echo $zonaId ?>";
      let fechaVta = $("#fechaVta").val();

      //Cambiarán de acuerdo a los permisos por ruta
      $('#precioVta').prop('readonly', true);
      obtenerPermisosRuta(rutaId);

      //Obtener precio del producto
      $("#productoVta").empty().append('<option value="0" selected disabled>Seleccione Opción</option>');
      $.ajax({
        data: { rutaId : rutaId },
        type: "GET",
        url: '../controller/Rutas/CargarProductosRuta.php', 
        dataType: "json",
        success: function(data){
          $.each(data,function(key, producto) {
            $("#productoVta").append('<option value='+producto.idproducto+'>'+ producto.nombre +'</option>');
            $("#productoVta").val(producto.idproducto).change();
          });        
        },
        error: function(data) {
          alertify.error('Ha ocurrido un error al cargar los productos');
        }
      });

      //Obtener inventario del producto
      obtenerInventario();
    });

    $("#productoVta").change(function() {
      let productoId = $("#productoVta").val();
      let rutaId = $("#rutaVta").val();
      let zonaId = "<?php echo $zonaId ?>";
      let fechaVta = $("#fechaVta").val();

      //Obtener precio del producto
      $.ajax({
        data: { productoId : productoId },
        type: "GET",
        url: '../controller/Productos/ObtenerPrecioProducto.php', 
        dataType: "json",
        success: function(data){
          $("#precioVta").val(parseFloat(data).toFixed(2));    
        },
        error: function(data) {
          alertify.error('Ha ocurrido un error al cargar el precio del producto');
        }
      });

      obtenerInventario();
    });

    //Validar formulario
    $("#formNuevaVenta" ).submit(function( event ) {
      let cantidadVta = parseFloat($("#cantidadVta").val());
      let pruebasVta = parseFloat($("#pruebasVta").val());
      let precioVta = parseFloat($("#precioVta").val());
      let inventarioVta = parseFloat($("#inventarioVta").val());   
      let productoId = parseFloat($("#productoVta").val());  
      let fechaVta = $("#fechaVta").val(); 

        if(cantidadVta <= 0){
          event.preventDefault();
          alertify.error("Ingresa una cantidad válida");
        }
        else if(new Date(fechaVta) > new Date()){
          event.preventDefault();
          alertify.error("No puedes registrar ventas en fechas posteriores");
        }
        else if(precioVta <= 0){
          event.preventDefault();
          alertify.error("Ingresa un precio válido");
        }
        else if(inventarioVta < 0.0001 || (cantidadVta+pruebasVta) > inventarioVta ){
          event.preventDefault();
          alertify.error("No tienes inventario suficiente");
        }
    });

    function obtenerInventario(){
      let rutaId = $("#rutaVta").val();
      let zonaId = "<?php echo $zonaId ?>";
      let fechaVta = $("#fechaVta").val();
      
      //Obtener inventario del producto
      $.ajax({
        data: { rutaId : rutaId, zonaId:zonaId,fechaVta:fechaVta},
        type: "GET",
        url: '../controller/Inventario/ObtenerInventarioGasolinaRuta.php', 
        dataType: "json",
        success: function(data){
          $("#inventarioVta").val(parseFloat(data).toFixed(2));  
        },
        error: function(data) {
          alertify.error('Ha ocurrido un error al cargar el inventario');
        }
      });
    }

    function limpiarNuevaVta() {
      $("#productoVta").empty().append('<option value="0" selected disabled>Seleccione Opción</option>');
      $("#rutaVta").val(0);
      $("#productoVta").val(0);
      
      //Lecturas y Porcentajes
      $("#inventarioVta").val(0.00);   

      //Detalles venta
      $("#cantidadVta").val(0);
      $("#precioVta").val(0);

      //Liquidación
      $("#totalVta").val(0);
      $("#totalVtaCredito").val(0);
      $("#totalVtaContado").val(0);

      //Ajustes en almacén
      $("#pruebasVta").val(0);
    }

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
</script>