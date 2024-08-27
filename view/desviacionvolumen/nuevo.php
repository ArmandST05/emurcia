<?php
$modelRuta = new ModelRuta();
$modelProveedor = new ModelProveedor();
$zonaId = $_SESSION['zonaId'];
$zonaTipo = $_SESSION["tipoZona"];

if($zonaTipo == 1){//Gas
  $rutas = $modelRuta->listaPorZonaEstatusTipo($zonaId, 1,3);
  $rutas = array_merge($rutas,$modelRuta->listaPorZonaEstatusTipo($zonaId, 1,4));
  $rutas = array_merge($rutas,$modelRuta->listaPorZonaEstatusTipo($zonaId, 1,5));
  $proveedores = $modelProveedor->listaPorTipoZona(1);//Gasolina

}else{//Gasolina
  $rutas = $modelRuta->listaPorZonaEstatusTipo($zonaId, 1,7);
  $rutas = array_merge($rutas,$modelRuta->listaPorZonaEstatusTipo($zonaId, 1,8));
  $rutas = array_merge($rutas,$modelRuta->listaPorZonaEstatusTipo($zonaId, 1,9));
  $proveedores = $modelProveedor->listaPorTipoZona(2);//Gasolina
}

//LAS COMPRAS DEL DÍA, SE UTILIZAN DEPENDIENDO DE LA FECHA DE DESCARGA NO DEPENDIENDO DE LA FECHA DE REGISTRO.
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="index.php?action=desviacionvolumen/index.php">Sistema Gestión de Medición</a> /
    <a href="#">Nuevo</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Nuevo registro gestión de medición</h1>
</div>

<!-- Content Row -->
<div class="row">
  <!-- Nuevo -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <div class="card-body">
        <form action="../controller/DesviacionVolumen/Insertar.php" method="POST" id="formDesviacion">
          <div class="row">
            <input type="hidden" name="zonaId" id="zonaId" value="<?php echo $zonaId ?>" required ?>
            <div class="col-md-3">
              <div class="form-group">
                <label>Almacén</label>
                <select class="form-control form-control-sm" name="rutaId" id="rutaId" aria-describedby="validationRuta" required>
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
                <select class="form-control form-control-sm" name="productoId" id="productoId" required>
                  <option value="0" selected disabled>Seleccione Opción</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Fecha</label>
                <input type="date" class="form-control form-control-sm" name="fecha" id="fecha" value="<?php echo date('Y-m-d') ?>" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Número factura/remisión</label>
                <input type="text" class="form-control form-control-sm" name="facturaRemision" id="facturaRemision">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Volumen factura a 20°C</label>
                <input type="number" value="0" min="0" step=".01" class="form-control form-control-sm" name="volumenFactura" id="volumenFactura" onchange="calcularVolumenDesviacion()">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Proveedor</label>
                <select class="form-control form-control-sm" name="proveedorId" id="proveedorId" required>
                  <option value="0" selected>--NO APLICA--</option>
                  <?php foreach ($proveedores as $proveedor) : ?>
                    <option value="<?php echo $proveedor['idproveedor'] ?>">
                      <?php echo $proveedor['nombre'] ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Transporte</label>
                <input type="text" class="form-control form-control-sm" name="transporte" id="transporte">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Tanque descarga</label>
                <input type="text" class="form-control form-control-sm" name="tanqueDescarga" id="tanqueDescarga">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Volumen descarga bruto</label>
                <input type="number" value="0" min="0" step=".01" class="form-control form-control-sm" name="volumenDescargaBruto" id="volumenDescargaBruto" onchange="calcularVolumenDesviacion()">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Venta durante descarga (Anotar litros)</label>
                <input type="number" value="0" min="0" step=".01" class="form-control form-control-sm" name="ventaDescarga" id="ventaDescarga">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Volumen desviación factura vs veeder root</label>
                <input type="text" class="form-control form-control-sm" name="volumenDesviacion" id="volumenDesviacion" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <label>Desviación no mayor al 0.5% entre factura vs veeder root</label>
              <div class="input-group input-group-sm mb-3">
                <input type="text" class="form-control form-control-sm" name="desviacion" id="desviacion" readonly>
                <div class="input-group-append">
                  <span class="input-group-text">%</span>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Inventario Inicial (Arranque 06:00)</label>
                <input type="number" min="0" step=".01" value="0" class="form-control form-control-sm" name="inventarioInicial" id="inventarioInicial" required onchange="calcularIncrementoCombustible()">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Compras del día</label>
                <input type="number" min="0" step=".01" value="0" class="form-control form-control-sm" name="comprasDia" id="comprasDia" onchange="calcularIncrementoCombustible()">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Incremento de combustible existente</label>
                <input type="number" value="0" min="0" step=".01" class="form-control form-control-sm" name="incrementoCombustible" id="incrementoCombustible" required readonly>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Inventario final (Cierre 22:00)</label>
                <input type="number" value="0" min="0" step=".01" class="form-control form-control-sm" name="inventarioFinal" id="inventarioFinal" required onchange="calcularLtsVendidosVeederRoot()">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Litros vendidos veeder root</label>
                <input type="number" value="0" min="0" step=".01" class="form-control form-control-sm" name="totalVendidoRoot" id="totalVendidoRoot" required readonly>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Litros sistema vendidos (I - Gas)</label>
                <input type="number" value="0" min="0" step=".01" class="form-control form-control-sm" name="totalVendidoSistema" id="totalVendidoSistema" required onchange="calcularDiferenciaVendido()">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Diferencia entre veeder root vs I Gas</label>
                <input type="number" value="0" min="0" step=".01" class="form-control form-control-sm" name="diferenciaVendido" id="diferenciaVendido" required readonly>
              </div>
            </div>
            <div class="col-md-6">
              <label>PORCENTAJE DE MERMA (No debe ser mayor al 5%) Según anexo 30 SAT</label>
              <div class="input-group input-group-sm mb-3">
                <input type="number" value="0" min="0" step=".01" class="form-control form-control-sm" name="porcentajeMerma" id="porcentajeMerma" required readonly>
                <div class="input-group-append">
                  <span class="input-group-text">%</span>
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

  (function() {
  'use strict';
      window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
          form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
              event.preventDefault();
              event.stopPropagation();
            }
            form.classList.add('was-validated');
          }, false);
        });
      }, false);
    })();

    var rutas = '<?php echo json_encode($rutas) ?>';

    $(document).ready(function(){
      var zonaId = "<?php echo $zonaId ?>";
    });

    $("#rutaId").change(function() {
      let rutaId = $("#rutaId").val();

      //Obtener precio del producto
      $("#productoId").empty().append('<option value="0" disabled>Seleccione Opción</option>');
      $.ajax({
        data: { rutaId : rutaId },
        type: "GET",
        url: '../controller/Rutas/CargarProductosRuta.php', 
        dataType: "json",
        success: function(data){
          $.each(data,function(key, producto) {
            $("#productoId").append('<option value='+producto.idproducto+'>'+ producto.nombre +'</option>');
            $("#productoId").val(producto.id).change();
          });    
          console.log($("#productoId").val());    
        },
        error: function(data) {
          alertify.error('Ha ocurrido un error al cargar los productos.');
        }
      });
    });

    //Validar formulario
    $("#formDesviacion" ).submit(function( event ) {
    });

    function calcularVolumenDesviacion(){
      var volumenFactura = (isNaN(parseFloat($("#volumenFactura").val()))) ? 0 : parseFloat($("#volumenFactura").val());
      var volumenDescargaBruto = (isNaN(parseFloat($("#volumenDescargaBruto").val()))) ? 0 : parseFloat($("#volumenDescargaBruto").val());

      var volumenDesviacion = 0;
      if(volumenFactura != 0){
        volumenDesviacion = volumenFactura - volumenDescargaBruto;
      }

      $("#volumenDesviacion").val(parseFloat(volumenDesviacion).toFixed(2));
      calcularDesviacion();
    }

    function calcularDesviacion(){
      var volumenDesviacion = (isNaN(parseFloat($("#volumenDesviacion").val()))) ? 0 : parseFloat($("#volumenDesviacion").val());
      var volumenFactura = (isNaN(parseFloat($("#volumenFactura").val()))) ? 0 : parseFloat($("#volumenFactura").val());

      var desviacion = 0;
      if(volumenFactura != 0){
        desviacion = (volumenDesviacion/volumenFactura)*100;
      }
      if(desviacion > 0.5){
        $("#desviacion").css("background-color","#CA2624");
        $("#desviacion").css("color","#FFFFFF");
      }
      else {
        $("#desviacion").css("background-color","#7FC83A");
        $("#desviacion").css("color","#000000");
      }

      $("#desviacion").val(parseFloat(desviacion).toFixed(2));
    }

    function calcularIncrementoCombustible(){
      var inventarioInicial = (isNaN(parseFloat($("#inventarioInicial").val()))) ? 0 : parseFloat($("#inventarioInicial").val());
      var comprasDia = (isNaN(parseFloat($("#comprasDia").val()))) ? 0 : parseFloat($("#comprasDia").val());

      var incrementoCombustible = inventarioInicial + comprasDia;

      $("#incrementoCombustible").val(parseFloat(incrementoCombustible).toFixed(2));
      calcularLtsVendidosVeederRoot();
    }

    function calcularLtsVendidosVeederRoot(){
      var incrementoCombustible = (isNaN(parseFloat($("#incrementoCombustible").val()))) ? 0 : parseFloat($("#incrementoCombustible").val());
      var inventarioFinal = (isNaN(parseFloat($("#inventarioFinal").val()))) ? 0 : parseFloat($("#inventarioFinal").val());

      var totalVendidoRoot = 0;
      if(incrementoCombustible != 0){
        totalVendidoRoot = incrementoCombustible - inventarioFinal;
      }

      $("#totalVendidoRoot").val(parseFloat(totalVendidoRoot).toFixed(2));
      calcularDiferenciaVendido();
    }

    
    function calcularDiferenciaVendido(){
      var totalVendidoRoot = (isNaN(parseFloat($("#totalVendidoRoot").val()))) ? 0 : parseFloat($("#totalVendidoRoot").val());
      var totalVendidoSistema = (isNaN(parseFloat($("#totalVendidoSistema").val()))) ? 0 : parseFloat($("#totalVendidoSistema").val());

      var diferenciaVendido = 0;
      if(totalVendidoRoot != 0){
        diferenciaVendido = totalVendidoRoot - totalVendidoSistema;
      }

      $("#diferenciaVendido").val(parseFloat(diferenciaVendido).toFixed(2));
      calcularPorcentajeMerma();
    }

    function calcularPorcentajeMerma(){
      var diferenciaVendido = (isNaN(parseFloat($("#diferenciaVendido").val()))) ? 0 : parseFloat($("#diferenciaVendido").val());
      var totalVendidoRoot = (isNaN(parseFloat($("#totalVendidoRoot").val()))) ? 0 : parseFloat($("#totalVendidoRoot").val());

      var porcentajeMerma = 0;
      if(diferenciaVendido != 0){
        porcentajeMerma = (diferenciaVendido/totalVendidoRoot)*100;
      }

      if(porcentajeMerma > 5){
        $("#porcentajeMerma").css("background-color","#CA2624");
        $("#porcentajeMerma").css("color","#FFFFFF");
      }
      else {
        $("#porcentajeMerma").css("background-color","#7FC83A");
        $("#porcentajeMerma").css("color","#000000");
      }

      $("#porcentajeMerma").val(parseFloat(porcentajeMerma).toFixed(2));
    }
</script>