<?php
$modelZona = new ModelZona();
$modelRuta = new ModelRuta();
$modelRubroVenta = new ModelRubroVenta();
$modelClienteDescuento = new ModelClienteDescuento();

if ($_SESSION["tipoUsuario"] == "su") {
  $zonas = $modelZona->obtenerZonasGas();

  $zonaId = (!empty($_GET["zonaId"])) ? $_GET["zonaId"] : $zonas[0]["idzona"];
} else if ($_SESSION["tipoUsuario"] == "mv") { //Es un usuario multizona de captura de ventas
  $zonas = $modelZona->obtenerZonasPorUsuario($_SESSION["id"]);

  $zonaId = (!empty($_GET["zonaId"])) ? $_GET["zonaId"] : $zonas[0]["idzona"];
} else {
  $zonaId = $_SESSION['zonaId'];
}

$rubrosVenta = $modelRubroVenta->index();
$rutas = $modelRuta->listaPorZonaVentaEstatus($zonaId, 1);
$clientesDescuento = $modelClienteDescuento->listaZonaEstatus($zonaId, 1);
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="index.php?action=ventas/index.php">Ventas Gas</a> /
    <a href="#">Nueva</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Nueva venta
    <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "mv") : ?>
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

  <!-- Nuevo Pedido -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <div class="card-body">
        <form action="../controller/Ventas/Insertar.php" method="POST" id="formNuevaVenta" enctype="multipart/form-data">
          <div class="row">
            <div class="col-md-12">
              <label class="bg-danger text-white text-bold">Verifica que el vendedor y ayudante sean los correctos para la venta. Si los nombres no coinciden o el vendedor/ayudante faltó ese día informa el administrador para que haga el cambio y puedas capturar tu venta correctamente. </label>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2 offset-md-11">
              <button type="button" class="btn btn-light btn-sm" data-toggle="tooltip" data-placement="top" title="Limpiar" onclick="limpiarNuevaVta()">Limpiar <i class="fas fa-broom"></i></button>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-2">
              <div class="row">
                <div class="form-group">
                  <label>Almacén</label>
                  <select class="form-control form-control-sm" name="rutaVta" id="rutaVta" aria-describedby="validationRutaVta" required>
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
                <div class="form-group">
                  <label>Producto</label>
                  <select class="form-control form-control-sm" name="productoVta" id="productoVta">
                    <option value="0" selected disabled>Seleccione Opción</option>
                  </select>
                </div>
                <div class="form-group" id="vendedorSelectDiv">
                  <label class="" for="vendedorSelect">Vendedor</label>
                  <select class="form-control form-control-sm" name="vendedorSelect" id="vendedorSelect">
                    <option value="" selected disable>Seleccione Opción</option>
                  </select>
                </div>
                <div class="form-group" id="vendedor-div">
                  <label class="" for="vendedor1Nombre">Vendedor</label>
                  <input class="form-control form-control-sm" type="text" id="vendedor1Nombre" readonly value="">
                  <input class="form-control form-control-sm" type="number" id="vendedor1Id" name="vendedor1Id" hidden value="">
                </div>
                <div class="form-group" id="ayudante-div">
                  <label class="" for="vendedor2">Ayudante</label>
                  <input class="form-control form-control-sm" type="text" id="vendedor2Nombre" readonly value="">
                  <input class="form-control form-control-sm" type="number" id="vendedor2Id" name="vendedor2Id" hidden value="">
                </div>
              </div>
            </div>

            <div class="col">
              <div class="row">
                <label class="bg-warning text-white text-bold" id="inventarioVtaLabel">Inventario: 0.00</label>
                <input type="hidden" id="inventarioVta" value="0.00">
              </div>
              <div id="divLecturas">
                <div class="row">
                  <div class="col-md-3 offset-md-3">
                    <label>Inicial</label>
                  </div>
                  <div class="col-md-3">
                    <label>Final</label>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3">
                    <label>Lecturas</label>
                  </div>
                  <div class="col-md-3">
                    <input type="number" class="form-control form-control-sm" name="lecturaInicialVta" id="lecturaInicialVta" min="0" step=".01" value="0" onchange="calcularCantidadVta()" readonly>
                  </div>
                  <div class="col-md-3">
                    <input type="number" class="form-control form-control-sm" name="lecturaFinalVta" id="lecturaFinalVta" min="0" step=".01" value="0" onchange="calcularCantidadVta()">
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3">
                    <label>Porcentajes</label>
                  </div>
                  <div class="col-md-3">
                    <input type="number" class="form-control form-control-sm" name="porcentajeInicialVta" id="porcentajeInicialVta" min="0" step=".01" value="0" readonly>
                  </div>
                  <div class="col-md-3">
                    <input type="number" class="form-control form-control-sm" name="porcentajeFinalVta" id="porcentajeFinalVta" min="0" step=".01" value="0">
                  </div>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-3">
                  <label>Cantidad</label>
                </div>
                <div class="col-md-3">
                  <input type="number" class="form-control form-control-sm rounded-right" name="cantidadVta" id="cantidadVta" min="0" step=".01" value="0" required onchange="calcularTotalVta()">
                  <div class="invalid-feedback">
                    Valor inválido.
                  </div>
                </div>
                <div class="col-md-3">
                  <label>Precio</label>
                </div>
                <div class="col-md-3">
                  <input type="number" class="form-control form-control-sm" name="precioVta" id="precioVta" min="0" step=".01" value="0" required onchange="calcularTotalVta();" <?php echo (array_search("ventas.precio-producto.editar", $_SESSION["permisos"]) !== false) ? "" : "readonly" ?>>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  <label>Descuentos</label>
                </div>
                <div class="col-md-6">
                  <label>Desc. Cantidad LTS</label>
                </div>
                <div class="col-md-3">
                  <label>Desc. Total $</label>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <label>Descuento Venta Contado</label>
                </div>
                <div class="col-md-6">
                  <input type="number" class="form-control form-control-sm" name="cantidadLPVtaContado" id="cantidadLPVtaContado" min="0" step=".01" value="0">
                </div>
                <div class="col-md-3">
                  <input type="number" class="form-control form-control-sm" name="descuentoTotalVtaContado" id="descuentoTotalVtaContado" min="0" step=".01" value="0" onchange="calcularTotalVtaContado()">
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  <label>Descuento Venta Crédito</label>
                </div>
                <div class="col-md-6">
                  <input type="number" class="form-control form-control-sm" name="cantidadLPVtaCredito" id="cantidadLPVtaCredito" min="0" step=".01" value="0">
                </div>
                <div class="col-md-3">
                  <input type="number" class="form-control form-control-sm" name="descuentoTotalVtaCredito" id="descuentoTotalVtaCredito" min="0" step=".01" value="0" onchange="calcularTotalVtaContado()">
                </div>
              </div>
              <div class="row">
                <div class="alert alert-danger col-md-12" role="alert">
                  Verifica que los LTS de descuento Crédito y descuento Contado sean correctos.
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-6 offset-md-6">
                  <label>Liquidación</label>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3 offset-md-6">
                  <label>Total Venta</label>
                </div>
                <div class="col-md-3">
                  <input type="number" class="form-control form-control-sm" name="totalVta" id="totalVta" min="0" step=".01" value="0" readonly>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3 offset-md-6">
                  <label>Venta de Crédito</label>
                </div>
                <div class="col-md-3">
                  <input type="number" class="form-control form-control-sm" name="totalVtaCredito" id="totalVtaCredito" min="0" step=".01" value="0" onchange="calcularTotalVtaContado()">
                </div>
              </div>
              <div class="row">
                <div class="col-md-3 offset-md-6">
                  <label>Venta de Contado</label>
                </div>
                <div class="col-md-3">
                  <input type="number" class="form-control form-control-sm" name="totalVtaContado" id="totalVtaContado" min="0" step=".01" value="0" readonly>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="container">
                <div class="card">
                  <div class="card-header">
                    Rubros
                  </div>
                  <div class="card-body" id="rubrosDiv">
                    <?php foreach ($rubrosVenta as $rubro) { ?>
                      <div class="row">
                        <div class="col">
                          <label><?php echo $rubro['nombre']; ?></label>
                        </div>
                        <div class="col">
                          <input type="number" class="form-control form-control-sm rubros-input" name="rubros[<?php echo $rubro['idrubroventa']; ?>]" id="rubro-<?php echo $rubro['idrubroventa']; ?>" data-id="<?php echo $rubro['idrubroventa']; ?>" min="0" step=".01" value="0.00">
                        </div>
                      </div>
                    <?php } ?>
                  </div>
                </div>
                <div class="card-footer text-muted">
                  <div class="row">
                    <div class="col">
                      <label>Total Rubros</label>
                    </div>
                    <div class="col">
                      <input type="number" class="form-control form-control-sm" name="totalRubrosVta" id="totalRubrosVta" min="0" value="0.00" readonly>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <div class="row">
                <div class="col-md-4">
                  <label>Ajustes en Almacén (Litros/Piezas)</label>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">
                  <label>Entradas</label>
                </div>
                <div class="col-md-2">
                  <input type="number" class="form-control form-control-sm" name="entradasVta" id="entradasVta" min="0" step=".01" value="0">
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">
                  <label>Otras Salidas</label>
                </div>
                <div class="col-md-2">
                  <input type="number" class="form-control form-control-sm" name="otrasSalidasVta" id="otrasSalidasVta" min="0" step=".01" value="0" onchange="calcularCantidadVta()">
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">
                  <label>Pruebas</label>
                </div>
                <div class="col-md-2">
                  <input type="number" class="form-control form-control-sm" name="pruebasVta" id="pruebasVta" min="0" step=".01" value="0">
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">
                  <label>Consumo Interno</label>
                </div>
                <div class="col-md-2">
                  <input type="number" class="form-control form-control-sm" name="consumoInternoVta" id="consumoInternoVta" min="0" step=".01" value="0" onchange="calcularCantidadVta()">
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">
                  <label>Traspasos</label>
                </div>
                <div class="col-md-2">
                  <input type="number" class="form-control form-control-sm" name="traspasosVta" id="traspasosVta" min="0" step=".01" value="0" onchange="calcularCantidadVta()">
                </div>
              </div>
            </div>
          </div>
          <div class="row">
                <div class="col-md-2">
                  <label>km inicial</label>
                </div>
                <div class="col-md-2">
                  <input type="number" class="form-control form-control-sm" name="traspasosVta" id="traspasosVta" min="0" step=".01" value="0" onchange="calcularCantidadVta()">
                </div>
                <div class="col-md-2">
                  <label>km final</label>
                </div>
                <div class="col-md-2">
                  <input type="number" class="form-control form-control-sm" name="traspasosVta" id="traspasosVta" min="0" step=".01" value="0" onchange="calcularCantidadVta()">
                </div>
          </div>
          <div class="row">
                <div class="col-md-2">
                  <label>Traspasos</label>
                </div>
                <div class="col-md-2">
                  <input type="number" class="form-control form-control-sm" name="traspasosVta" id="traspasosVta" min="0" step=".01" value="0" onchange="calcularCantidadVta()">
                </div>
              </div>
          <hr>
          <div class="row">
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-12">
        <label>Desglose de autoconsumo por ruta:</label>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <select class="form-control form-control-sm" name="selectRuta" id="selectRuta">
          <?php foreach ($rutas as $ruta) : ?>
            <option value="<?php echo $ruta['idruta'] ?>"
              data-nombre="<?php echo $ruta['clave_ruta'] ?>">
              <?php echo $ruta['clave_ruta'] ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <button type="button" class="btn btn-warning btn-sm" onclick="agregarRutaAutoconsumo()"><i class="fas fa-plus"></i></button>
      </div>
    </div>
    <div id="listaRutasAutoconsumo"></div> <!-- Aquí se agregan las filas -->
  </div>
</div>

          <hr>
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-12">
                  <label>Desglose de descuentos por cliente:</label>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <select class="form-control form-control-sm" name="selectClienteDescuento" id="selectClienteDescuento">
                    <?php foreach ($clientesDescuento as $clienteDescuento) : ?>
                      <option value="<?php echo $clienteDescuento['idclientedescuento'] ?>" data-nombre="<?php echo $clienteDescuento['nombre'] ?>" data-descuento-id="<?php echo $clienteDescuento['descuento_id'] ?>" data-descuento-cantidad="<?php echo $clienteDescuento['descuento_cantidad'] ?>">
                        <?php echo $clienteDescuento['nombre'] ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-2">
                  <button type="button" class="btn btn-warning btn-sm" onclick="agregarClienteDescuento()"><i class="fas fa-plus"></i></button>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-12">
                  <label class="bg-warning text-white text-bold">Asegúrate de capturar los datos correctamente, no podrás modificarlos después.</label>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">
                  <label>CLIENTE</label>
                </div>
                <div class="col-md-1">
                  <label>DESCUENTO</label>
                </div>
                <div class="col-md-2">
                  <label>CANTIDAD</label>
                </div>
                <div class="col-md-2">
                  <label>TOTAL</label>
                </div>
              </div>
              <div id="listaClientesDescuento">

              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-1 offset-md-11">
              <input type="hidden" value="<?php echo $zonaId ?>" name="zonaId">
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
      $("#divLecturas").hide();
      $('#vendedorSelectDiv').hide();
      $('#rubros-6').prop('readonly', false);

      $('#selectClienteDescuento').select2({});
    });

    $("#rutaVta").change(function() {
      let rutaId = $("#rutaVta").val();
      let tipoRuta = $(this).find(':selected').data('tipo');

      //Cambiarán de acuerdo a los permisos por ruta
      $('#precioVta').prop('readonly', true);
      $('#lecturaInicialVta').prop('readonly', true);
      obtenerPermisosRuta(rutaId);
      //Escondemos el ayudante en caso que sea una estación de carburación y debemos mostrar el select de empleados de esa estación.
      if(tipoRuta==5){
        $('#ayudante-div').hide();
        $('#vendedor-div').hide();
        $('#vendedorSelectDiv').show();
      }else{
        $('#ayudante-div').show();
        $('#vendedor-div').show();
        $('#vendedorSelectDiv').hide();
      }

      //Tipo ruta pipas() 1) o venta de litros plantas-lts (4) estación carburación lts (5)
      if(tipoRuta == 1 || tipoRuta == 4 || tipoRuta == 5){
        $("#divLecturas").show();
        $("#cantidadVta").prop('readonly', true);
        $("#rubro-6").prop('readonly', true);
        obtenerUltimasLecturasRuta(rutaId);
      }
      //Tipo rutas cilindreras
      else {
        $("#divLecturas").hide(); 
        $("#cantidadVta").prop('readonly', false);
        $("#rubro-6").prop('readonly', false);
      }

      $("#productoVta").empty().append('<option value="0" selected disabled>Seleccione Opción</option>');

      $.ajax({
        data: { rutaId: rutaId },
        type: "GET",
        url: '../controller/Rutas/CargarVendedores.php',
        dataType: "json",
        success: function (data) {

          // Verificamos si hay datos de vendedores
          if (data && data.length > 0) {
            $("#vendedorSelect").empty();
              // Tomamos el nombre del primer vendedor
              var primerVendedor = data[0].vendedor1_nombre;
              var segundoVendedor = data[0].vendedor2_nombre;
              

              //Escondemos el ayudante en caso que sea una estación de carburación y debemos mostrar el select de empleados de esa estación.
              if (tipoRuta == 5) { 
                $("#vendedorSelect").append('<option value='+data[0].vendedor1_id+'>'+ primerVendedor +'</option>');
                $("#vendedorSelect").append('<option value='+data[0].vendedor2_id+'>'+ segundoVendedor +'</option>');
              } else {
                // Asignamos el nombre del primer vendedor al input
                $("#vendedor1Nombre").val(primerVendedor);
                $("#vendedor2Nombre").val(segundoVendedor);

                $("#vendedor1Id").val(data[0].vendedor1_id);
                $("#vendedor2Id").val(data[0].vendedor2_id);
              }

          } else {
              // Si no hay vendedores disponibles, puedes manejarlo aquí
          }
        },
        error: function (data) {
          alertify.error('Ha ocurrido un error al cargar los vendedores');
        }
        });


      $.ajax({
        data: { rutaId : rutaId },
        type: "GET",
        url: '../controller/Rutas/CargarProductosRuta.php', 
        dataType: "json",
        success: function(data){
          $.each(data,function(key, producto) {
            $("#productoVta").append('<option value='+producto.idproducto+'>'+ producto.nombre +'</option>');
          });        
        },
        error: function(data) {
          alertify.error('Ha ocurrido un error al cargar los productos');
        }
      });

    });



    $("#productoVta").change(function() {
      let productoId = $("#productoVta").val();
      let rutaId = $("#rutaVta").val();

      //Obtener precio del producto, si se le asignó un precio especial a la ruta se obtiene, si no, el precio predeterminado de la zona.
      $.ajax({
        data: { 
          productoId : productoId,
          rutaId : rutaId,
          zonaId : "<?php echo $zonaId ?>",
        },
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
      //Obtener inventario del producto
      $.ajax({
        data: { rutaId : rutaId, productoId: productoId },
        type: "GET",
        url: '../controller/Inventario/ObtenerInventarioRuta.php', 
        dataType: "json",
        success: function(data){
          $("#inventarioVtaLabel").text("Inventario: "+parseFloat(data).toFixed(2)); 
          $("#inventarioVta").val(parseFloat(data).toFixed(2));  
          $("#porcentajeInicialVta").val(parseFloat(data).toFixed(2)); 
        },
        error: function(data) {
          alertify.error('Ha ocurrido un error al cargar el inventario');
        }
      });
  
    });

    //Validar formulario
    $("#formNuevaVenta" ).submit(function( event ) {
      let totalRubrosVta = parseFloat($("#totalRubrosVta").val());
      let cantidadVta = parseFloat($("#cantidadVta").val());
      let precioVta = parseFloat($("#precioVta").val());
      let inventarioVta = parseFloat($("#inventarioVta").val());   
      let productoId = parseFloat($("#productoVta").val());  

      let otrasSalidasVta = parseFloat($("#otrasSalidasVta").val());
      let consumoInternoVta = parseFloat($("#consumoInternoVta").val());
      let traspasosVta = parseFloat($("#traspasosVta").val());
      let pruebasVta = parseFloat($("#pruebasVta").val());
      let totalSalidasVta = otrasSalidasVta + consumoInternoVta + traspasosVta + pruebasVta;

      //Comparar descuentos otorgados contra totales de descuentos créditos y contado
        let totalDescuentosCliente = 0;
        $(".totalDescuentoCliente").each(function(){
            totalDescuentosCliente += parseFloat($(this).val());
        });
        totalDescuentosCliente = parseFloat(totalDescuentosCliente).toFixed(2);
        let totalCapturadoDescuentosCliente = parseFloat(parseFloat($("#descuentoTotalVtaContado").val()) + parseFloat($("#descuentoTotalVtaCredito").val())).toFixed(2);
        
        //Comparar cantidad descuentos otorgados contra cantidad litros de descuentos créditos y contado
        let cantidadDescuentosCliente = 0;
        $(".cantidadDescuentoCliente").each(function(){
          cantidadDescuentosCliente += parseFloat($(this).val());
        });
        cantidadDescuentosCliente = parseFloat(cantidadDescuentosCliente).toFixed(2);
        let cantidadCapturadaDescuentosCliente = parseFloat(parseFloat($("#cantidadLPVtaContado").val()) + parseFloat($("#cantidadLPVtaCredito").val())).toFixed(2);
       








       /* if(totalDescuentosCliente != totalCapturadoDescuentosCliente){
          event.preventDefault();
          alertify.error("Coloca correctamente el desglose de los descuentos que otorgaste.");
        }
        else if(cantidadDescuentosCliente != cantidadCapturadaDescuentosCliente){
          event.preventDefault();
          alertify.error("Coloca correctamente el desglose de las cantidades (litros) de descuentos que otorgaste. Separadas por contado y crédito.");
        }
        else if(cantidadVta <= 0 && totalSalidasVta <= 0){
          event.preventDefault();
          alertify.error("Ingresa una cantidad válida");
        }
        else if(precioVta <= 0){
          event.preventDefault();
          alertify.error("Ingresa un precio válido");
        }
        if(totalRubrosVta != cantidadVta){
          event.preventDefault();
          alertify.error("El Total de Rubros tiene que ser igual a la CANTIDAD de la venta");
        }
        else if(productoId != 4 && (inventarioVta < 0.0001 || cantidadVta > inventarioVta )){
          event.preventDefault();
          alertify.error("No tienes inventario suficiente");
        }*/
    });

    function calcularCantidadVta(){
      let productoId = parseFloat($("#productoVta").val());  
        //Producto == Litros  
        if(productoId == 4){
        let otrasSalidasVta = parseFloat($("#otrasSalidasVta").val());
        let consumoInternoVta = parseFloat($("#consumoInternoVta").val());
        let traspasosVta = parseFloat($("#traspasosVta").val());
        let pruebasVta = parseFloat($("#pruebasVta").val());

        let totalSalidasVta = otrasSalidasVta + consumoInternoVta + traspasosVta + pruebasVta;

        let lecturaFinalVta = parseFloat($("#lecturaFinalVta").val());
        let lecturaInicialVta = parseFloat($("#lecturaInicialVta").val());
        let cantidadVta = lecturaFinalVta - lecturaInicialVta;

        let cantidadRealVta = parseFloat(cantidadVta - totalSalidasVta).toFixed(2);
        $("#cantidadVta").val(cantidadRealVta);
        calcularTotalVta();
      }
    }

    function limpiarNuevaVta() {
      $("#divLecturas").hide();
      $("#productoVta").empty().append('<option value="0" selected disabled>Seleccione Opción</option>');
      $("#rutaVta").val(0);
      $("#productoVta").val(0);
      
      //Lecturas y Porcentajes
      $("#inventarioVtaLabel").text("Inventario: 0.00");
      $("#inventarioVta").val(0.00);   
      $("#lecturaInicialVta").val(0);
      $("#lecturaFinalVta").val(0);
      $("#porcentajeInicialVta").val(0);
      $("#porcentajeFinalVta").val(0);

      //Detalles venta
      $("#cantidadVta").val(0);
      $("#precioVta").val(0);
      $("#cantidadLPVtaContado").val(0);
      $("#descuentoTotalVtaContado").val(0);
      $("#cantidadLPVtaCredito").val(0);
      $("#descuentoTotalVtaCredito").val(0);

      //Liquidación
      $("#totalVta").val(0);
      $("#totalVtaCredito").val(0);
      $("#totalVtaContado").val(0);

      //Rubros
      $(".rubros-input").each(function(){
        $(this).val(0);
      });
      $("#totalRubrosVta").val(0);

      //Ajustes en almacén
      $("#entradasVta").val(0);
      $("#otrasSalidasVta").val(0);
      $("#pruebasVta").val(0);
      $("#consumoInternoVta").val(0);
      $("#traspasosVta").val(0);

      $("#listaClientesDescuento").empty();
    }

    //Calcular total rubros venta
    $(".rubros-input").change(function() {
      var totalRubros = 0;
      $(".rubros-input").each(function(){
        totalRubros = totalRubros + parseFloat($(this).val());
      });
      $("#totalRubrosVta").val(parseFloat(totalRubros).toFixed(2));
    });

    function calcularTotalVta(){
      var totalVta = parseFloat($("#cantidadVta").val()) * parseFloat($("#precioVta").val());
      $("#totalVta").val(parseFloat(totalVta).toFixed(2));
      calcularTotalVtaContado();
    }

    function calcularTotalVtaContado(){
      let descuentoTotalVtaContado = parseFloat($("#descuentoTotalVtaContado").val());
      let descuentoTotalVtaCredito = parseFloat($("#descuentoTotalVtaCredito").val());
      let totalVtaCredito = parseFloat($("#totalVtaCredito").val());
      let totalVta = parseFloat($("#totalVta").val());

      let totalVtaContado = totalVta - (descuentoTotalVtaContado + descuentoTotalVtaCredito + totalVtaCredito);
      $("#totalVtaContado").val(parseFloat(totalVtaContado).toFixed(2));
    }

    function obtenerUltimasLecturasRuta(rutaId){
      $.ajax({
        data: { rutaId : rutaId },
        type: "GET",
        url: '../controller/Rutas/ObtenerUltimasLecturas.php', 
        dataType: "json",
        success: function(data){
          $("#lecturaInicialVta").val(data.lecturaInicial);   
          $("#porcentajeInicialVta").val(data.porcentajeInicial);    
        },
        error: function(data) {
          alertify.error('Ha ocurrido un error al cargar las últimas lecturas de la unidad/ruta');
        }
      });
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
    function agregarRutaAutoconsumo() {
  var rutaId = $("#selectRuta option:selected").val();
  var rutaNombre = $("#selectRuta option:selected").data("nombre");

  if ($("#rowRutaAutoconsumo" + rutaId).length > 0) {
    alertify.error('Ya agregaste esta ruta');
    return;
  }

  let newRow = '<div class="row mt-2" id="rowRutaAutoconsumo' + rutaId + '">';
  newRow += '<div class="col-md-3"><label>' + rutaNombre + '</label></div>';

  newRow += '<div class="col-md-3">';
  newRow += '<label>Litros Autoconsumo</label>';
  newRow += '<input type="number" class="form-control form-control-sm litrosAutoconsumo" name="rutasAutoconsumo['+rutaId+'][litros]" min="0" step=".01" value="0" onchange="calcularRutaAutoconsumo('+rutaId+')">';
  newRow += '</div>';
  newRow += '<div class="col-md-2">';
newRow += '<label>Km inicial</label>';
newRow += '<input type="text" class="form-control form-control-sm" id="kmi'+rutaId+'" name="rutasAutoconsumo['+rutaId+'][kmi]" onchange="calcularRutaAutoconsumo('+rutaId+')">';
newRow += '</div>';

newRow += '<div class="col-md-2">';
newRow += '<label>Km final</label>';
newRow += '<input type="text" class="form-control form-control-sm" id="kmf'+rutaId+'" name="rutasAutoconsumo['+rutaId+'][kmf]" onchange="calcularRutaAutoconsumo('+rutaId+')">';
newRow += '</div>';

newRow += '<div class="col-md-2">';
newRow += '<label>Costo/litro</label>';
newRow += '<input type="text" class="form-control form-control-sm" id="costo'+rutaId+'" name="rutasAutoconsumo['+rutaId+'][costo]" onchange="calcularRutaAutoconsumo('+rutaId+')">';
newRow += '</div>';

newRow += '<div class="col-md-2">';
newRow += '<label>Total</label>';
newRow += '<input type="text" class="form-control form-control-sm" id="total'+rutaId+'" disabled>';
newRow += '</div>';

newRow += '<div class="col-md-2">';
newRow += '<label>Rendimiento</label>';
newRow += '<input type="text" class="form-control form-control-sm" id="rendi'+rutaId+'" disabled>';
newRow += '</div>';


  newRow += '<div class="col-md-1"><button type="button" class="btn btn-danger btn-sm" onclick="eliminarRutaAutoconsumo(' + rutaId + ')"><i class="fas fa-trash"></i></button></div>';
  newRow += '</div>';

  $("#listaRutasAutoconsumo").append(newRow);
}

function validarTotalAutoconsumo() {
  let consumoMaximo = parseFloat($("#consumoInternoVta").val()) || 0;
  let totalAutoconsumo = 0;

  $(".litrosAutoconsumo").each(function () {
    let val = parseFloat($(this).val()) || 0;
    totalAutoconsumo += val;
  });

  if (totalAutoconsumo > consumoMaximo) {
    alertify.error("La suma de litros de autoconsumo no puede ser mayor que el consumo interno.");
    
    // Limpiar el último campo modificado
    $(event.target).val(0);

    validarTotalAutoconsumo(); // volver a validar tras limpiar
  }
}

function eliminarRutaAutoconsumo(rutaId) {
  $("#rowRutaAutoconsumo" + rutaId).remove();
}
function calcularRutaAutoconsumo(rutaId) {
  var costo_litro = parseFloat(document.getElementById('costo' + rutaId).value) || 0;
  var kmi = parseFloat(document.getElementById('kmi' + rutaId).value) || 0;
  var kmf = parseFloat(document.getElementById('kmf' + rutaId).value) || 0;
  var litrosInput = document.querySelector(`input[name='rutasAutoconsumo[${rutaId}][litros]']`);
  var litros = parseFloat(litrosInput ? litrosInput.value : 0) || 0;

  var total = costo_litro * litros;
  var rendimiento = litros > 0 ? (kmf - kmi) / litros : 0;

  document.getElementById('total' + rutaId).value = total.toFixed(2);
  document.getElementById('rendi' + rutaId).value = rendimiento.toFixed(2);
}

    //CLIENTES DESCUENTO
    function agregarClienteDescuento(){
      var clienteId = $("#selectClienteDescuento option:selected").val();
      var clienteNombre = $("#selectClienteDescuento option:selected").data("nombre");
      var clienteDescuentoCantidad = $("#selectClienteDescuento option:selected").data("descuento-cantidad");
      var clienteDescuentoId = $("#selectClienteDescuento option:selected").data("descuento-id");

      let newRow = '<div class="row" id="rowClienteDescuento'+clienteId+'">';
          newRow += '<div class="col-md-2"><label>'+clienteNombre+'</label></div>';
          newRow += '<div class="col-md-1"><input type="number" class="form-control form-control-sm" name="clientesDescuento['+clienteId+']['+"descuento"+']" id="descuentoClienteValor'+clienteId+'" min="0" step=".01" value="'+clienteDescuentoCantidad+'" readonly required><input type="hidden" name="clientesDescuento['+clienteId+']['+"descuentoId"+']" id="descuentoClienteId'+clienteId+'" value="'+clienteDescuentoId+'"></div>';
          newRow += '<div class="col-md-2"><input type="number" class="form-control form-control-sm cantidadDescuentoCliente" name="clientesDescuento['+clienteId+']['+"cantidad"+']" id="descuentoClienteCantidad'+clienteId+'" min="0" step=".01" value="0" onchange="calcularTotalDescuentoCliente('+clienteId+')" required></div>';
          newRow += '<div class="col-md-2"><input type="number" class="form-control form-control-sm totalDescuentoCliente" name="clientesDescuento['+clienteId+']['+"total"+']" id="descuentoTotal'+clienteId+'" min="0" step=".01" value="0" readonly required></div>';
          newRow += '</div>';
      if($("#rowClienteDescuento"+clienteId).length == 0){
        $("#listaClientesDescuento").append(newRow);
      }else{
        alertify.error('Ya agregaste este detalle del cliente');
      }
    }
    //CLIENTES DESCUENTO
    function calcularTotalDescuentoCliente(clienteId){
      let descuento = (isNaN(parseFloat($("#descuentoClienteValor"+clienteId).val()))) ? 0: $("#descuentoClienteValor"+clienteId).val();
      let cantidad = (isNaN(parseFloat($("#descuentoClienteCantidad"+clienteId).val()))) ? 0: $("#descuentoClienteCantidad"+clienteId).val();
      $("#descuentoTotal"+clienteId).val(parseFloat(descuento * cantidad).toFixed(2));
    }

    function seleccionZona(){
      window.location.href = "index.php?action=ventas/nuevo.php&zonaId="+ $("#zonaId").val();
    }
</script>