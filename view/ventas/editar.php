<?php
$modelVenta = new ModelVenta();
$modelClienteDescuento = new ModelClienteDescuento();
$modelRuta = new ModelRuta();
$modelEmpleado = new ModelEmpleado();
$ventaId = $_GET['id'];

$venta = $modelVenta->obtenerVentaPorId($ventaId);
$venta = reset($venta);
$zonaId = $venta["zona_id"];

$rubrosVenta = $modelVenta->obtenerRubrosPorVentaId($ventaId);
$fechaActual = date("Y-m-d");

$clientesDescuentoVenta = $modelClienteDescuento->listaVentaId($ventaId); //Obtener el detalle de los descuentos por cliente
$clientesDescuento = $modelClienteDescuento->listaZonaEstatus($venta["zona_id"], 1);
if ($venta == NULL || ($_SESSION["tipoUsuario"] == "u" && $venta["fecha"] != $fechaActual)) {
  echo "<script> 
          alert('No puedes editar esta venta');
          window.location.href = 'index.php?action=ventas/index.php';
        </script>";
}

$ruta = $modelRuta->obtenerRutaId($venta["ruta_id"]);

$vendedorVenta = $modelVenta->obtenerVentaEmpleadoPorTipo($ventaId, 0);
$vendedorVentaId = (isset($vendedorVenta)) ? $vendedorVenta["idempleado"] : "";
$ayudanteVenta = $modelVenta->obtenerVentaEmpleadoPorTipo($ventaId, 1);
$ayudanteVentaId = (isset($ayudanteVenta)) ? $ayudanteVenta["idempleado"] : "";

if ($_SESSION["tipoUsuario"] == "su") {
  $vendedores1 = $modelEmpleado->obtenerEmpleadosZonaTipoRuta($venta["zona_id"], $ruta['tipo_ruta_id'], 1, $vendedorVentaId); //Principal
  $vendedores2 = $modelEmpleado->obtenerEmpleadosZonaTipoRuta($venta["zona_id"], $ruta['tipo_ruta_id'], 2, $ayudanteVentaId); //Ayudante
} else {
  $empleado1 = $modelEmpleado->obtenerEmpleadoPorId($vendedorVentaId);
  if ($empleado1) {
    $vendedores1 = [(object)$empleado1];
  } else {
    $vendedores1 = null;
  }

  $empleado2 = $modelEmpleado->obtenerEmpleadoPorId($ayudanteVentaId);
  if ($empleado2) {
    $vendedores2 = [(object)$empleado2];
  } else {
    $vendedores2 = null;
  }
}
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="index.php?action=ventas/index.php">Ventas Gas</a> /
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
        <?php if ($_SESSION["tipoUsuario"] == "su") : ?>
          <div class="row">
            <div class="alert alert-warning col-md-12" role="alert">
              Sólo se permite editar la fecha de la venta si no se ha generado una venta posterior en esa ruta, para evitar irregularidades.
            </div>
          </div>
        <?php endif; ?>
        <form action="../controller/Ventas/Actualizar.php" method="POST" id="formEditarVenta">
          <div class="row">
            <div class="col-md-12">
              <label class="bg-danger text-white text-bold">Verifica que el vendedor y ayudante sean los correctos para la venta. Si los nombres no coinciden o el vendedor/ayudante faltó ese día informa el administrador para que haga el cambio y puedas capturar tu venta correctamente. </label>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2">
              <div class="row">
                <?php if ($_SESSION["tipoUsuario"] == "su") : ?>
                  <div class="form-group">
                    <label>Fecha</label>
                    <input type="date" class="form-control form-control-sm" name="fechaVta" id="fechaVta" value="<?php echo $venta["fecha"] ?>">
                  </div>
                <?php endif; ?>
                <div class="form-group">
                  <label>Almacén</label>
                  <input type="text" class="form-control form-control-sm" name="rutaVta" id="rutaVta" value="<?php echo $venta["ruta_nombre"] ?>" data-tipo="<?php echo $venta['tipo_ruta_id'] ?>" data-id="<?php echo $venta['ruta_id'] ?>" readonly>
                </div>
                <div class="form-group">
                  <label>Producto</label>
                  <input type="text" class="form-control form-control-sm" name="productoVta" id="productoVta" value="<?php echo $venta["producto_nombre"] ?>" data-id="<?php echo $venta['producto_id'] ?>" readonly required>
                </div>
                <div class="form-group">
                  <label>Vendedor</label>
                  <select class="form-control form-control-sm" name="vendedorSelect" id="vendedorSelect">
                    <?php if ($_SESSION["tipoUsuario"] == "su" || ($_SESSION["tipoUsuario"] != "su" && !$vendedores1)) : ?>
                      <option <?php echo ($vendedorVentaId == "") ? "selected" : ""; ?> value="">Sin asignación</option>
                    <?php endif; ?>
                    <?php
                    if ($vendedores1) :
                      foreach ($vendedores1 as $vendedor) : ?>
                        <option value="<?= $vendedor->idempleado ?>" <?php echo ($vendedor->idempleado == $vendedorVentaId) ? "selected" : ""; ?>>
                          <?= $vendedor->nombre ?>
                        </option>
                    <?php
                      endforeach;
                    endif;
                    ?>
                  </select>
                </div>
                <?php if ($ruta["tipo_ruta_id"] != 5) : ?>
                  <div class="form-group">
                    <label>Ayudante</label>
                    <select class="form-control form-control-sm" name="ayudanteSelect" id="ayudanteSelect">
                      <?php if ($_SESSION["tipoUsuario"] == "su" || ($_SESSION["tipoUsuario"] != "su" && !$vendedores2)) : ?>
                        <option value="" <?php echo ($ayudanteVentaId == "") ? "selected" : ""; ?>>Sin asignación</option>
                      <?php endif; ?>
                      <?php
                      if ($vendedores2) :
                        foreach ($vendedores2 as $vendedor2) : ?>
                          <option value="<?= $vendedor2->idempleado ?>" <?php echo ($vendedor2->idempleado == $ayudanteVentaId) ? "selected" : ""; ?>>
                            <?= $vendedor2->nombre ?>
                          </option>
                      <?php
                        endforeach;
                      endif;
                      ?>
                    </select>
                  </div>
                <?php endif; ?>
              </div>
            </div>
            <div class="col">
              <div class="row">
                <label class="bg-warning" id="inventarioVtaLabel">Inventario: 0.00</label>
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
                    <input type="number" class="form-control form-control-sm" name="lecturaInicialVta" id="lecturaInicialVta" min="0" step=".01" value="<?php echo $venta['lectura_inicial'] ?>" onchange="calcularCantidadVta()">
                  </div>
                  <div class="col-md-3">
                    <input type="number" class="form-control form-control-sm" name="lecturaFinalVta" id="lecturaFinalVta" min="0" step=".01" value="<?php echo $venta['lectura_final'] ?>" onchange="calcularCantidadVta()">
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3">
                    <label>Porcentajes</label>
                  </div>
                  <div class="col-md-3">
                    <input type="number" class="form-control form-control-sm" name="porcentajeInicialVta" id="porcentajeInicialVta" min="0" step=".01" value="<?php echo $venta['porcentaje_inicial'] ?>" readonly>
                  </div>
                  <div class="col-md-3">
                    <input type="number" class="form-control form-control-sm" name="porcentajeFinalVta" id="porcentajeFinalVta" min="0" step=".01" value="<?php echo $venta['porcentaje_final'] ?>">
                  </div>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-3">
                  <label>Cantidad</label>
                </div>
                <div class="col-md-3">
                  <input type="number" class="form-control form-control-sm rounded-right" name="cantidadVta" id="cantidadVta" min="0" step=".01" value="<?php echo $venta["cantidad"]; ?>" required onchange="calcularTotalVta()">
                  <div class="invalid-feedback">
                    Valor inválido.
                  </div>
                </div>
                <div class="col-md-3">
                  <label>Precio</label>
                </div>
                <div class="col-md-3">
                  <input type="number" class="form-control form-control-sm" name="precioVta" id="precioVta" min="0" step=".01" value="<?php echo $venta['precio']; ?>" required onchange="calcularTotalVta();" <?php echo (array_search("ventas.precio-producto.editar", $_SESSION["permisos"]) !== false) ? "" : "readonly" ?>>
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
                  <input type="number" class="form-control form-control-sm" name="cantidadLPVtaContado" id="cantidadLPVtaContado" min="0" step=".01" value="<?php echo $venta['cantidad_venta_contado'] ?>">
                </div>
                <div class="col-md-3">
                  <input type="number" class="form-control form-control-sm" name="descuentoTotalVtaContado" id="descuentoTotalVtaContado" min="0" step=".01" value="<?php echo $venta['descuento_total_venta_contado'] ?>" onchange="calcularTotalVtaContado()">
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  <label>Descuento Venta Crédito</label>
                </div>
                <div class="col-md-6">
                  <input type="number" class="form-control form-control-sm" name="cantidadLPVtaCredito" id="cantidadLPVtaCredito" min="0" step=".01" value="<?php echo $venta['cantidad_venta_credito'] ?>">
                </div>
                <div class="col-md-3">
                  <input type="number" class="form-control form-control-sm" name="descuentoTotalVtaCredito" id="descuentoTotalVtaCredito" min="0" step=".01" value="<?php echo $venta['descuento_total_venta_credito'] ?>" onchange="calcularTotalVtaContado()">
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
                  <input type="number" class="form-control form-control-sm" name="totalVtaCredito" id="totalVtaCredito" min="0" step=".01" value="<?php echo $venta["total_venta_credito"] ?>" onchange="calcularTotalVtaContado()">
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
                          <input type="number" class="form-control form-control-sm rubros-input" name="rubros[<?php echo $rubro['idrubroventa']; ?>]" id="rubro-<?php echo $rubro['idrubroventa']; ?>" data-id="<?php echo $rubro['idrubroventa']; ?>" min="0" step=".01" value="<?php echo ($rubro['cantidad'] > 0) ? $rubro['cantidad'] : "0.00" ?>" onchange="calcularTotalRubros()">
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
                  <input type="number" class="form-control form-control-sm" name="entradasVta" id="entradasVta" min="0" step=".01" value="<?php echo $venta["entradas_almacen"] ?>">
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">
                  <label>Otras Salidas</label>
                </div>
                <div class="col-md-2">
                  <input type="number" class="form-control form-control-sm" name="otrasSalidasVta" id="otrasSalidasVta" min="0" step=".01" value="<?php echo $venta["otras_salidas"] ?>" onchange="calcularCantidadVta()">
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">
                  <label>Pruebas</label>
                </div>
                <div class="col-md-2">
                  <input type="number" class="form-control form-control-sm" name="pruebasVta" id="pruebasVta" min="0" step=".01" value="<?php echo $venta["pruebas"] ?>">
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">
                  <label>Consumo Interno</label>
                </div>
                <div class="col-md-2">
                  <input type="number" class="form-control form-control-sm" name="consumoInternoVta" id="consumoInternoVta" min="0" step=".01" value="<?php echo $venta["consumo_interno"] ?>" onchange="calcularCantidadVta()">
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">
                  <label>Traspasos</label>
                </div>
                <div class="col-md-2">
                  <input type="number" class="form-control form-control-sm" name="traspasosVta" id="traspasosVta" min="0" step=".01" value="<?php echo $venta["traspasos"] ?>" onchange="calcularCantidadVta()">
                </div>
              </div>
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
                <?php foreach ($clientesDescuentoVenta as $clienteDescuentoVenta) : ?>
                  <div class="row" id="rowClienteDescuento<?php echo $clienteDescuentoVenta["cliente_id"] ?>">
                    <div class="col-md-2"><label><?php echo $clienteDescuentoVenta["cliente_nombre"] ?></label></div>
                    <div class="col-md-1">
                      <input type="number" class="form-control form-control-sm" name="clientesDescuento[<?php echo $clienteDescuentoVenta["cliente_id"] ?>][descuento]" id="descuentoClienteValor<?php echo $clienteDescuentoVenta["cliente_id"] ?>" min="0" step=".01" value="<?php echo $clienteDescuentoVenta["descuento_cantidad"] ?>" readonly required>
                      <input type="hidden" name="clientesDescuento[<?php echo $clienteDescuentoVenta["cliente_id"] ?>][descuentoId]" id="descuentoClienteId<?php echo $clienteDescuentoVenta["cliente_id"] ?>" value="<?php echo $clienteDescuentoVenta["descuento_id"] ?>">
                    </div>
                    <div class="col-md-2">
                      <input type="number" class="form-control form-control-sm cantidadDescuentoCliente" name="clientesDescuento[<?php echo $clienteDescuentoVenta["cliente_id"] ?>][cantidad]" id="descuentoClienteCantidad<?php echo $clienteDescuentoVenta["cliente_id"] ?>" min="0" step=".01" value="<?php echo $clienteDescuentoVenta["cantidad"] ?>" onchange="calcularTotalDescuentoCliente(<?php echo $clienteDescuentoVenta['cliente_id'] ?>)" required>
                    </div>
                    <div class="col-md-2">
                      <input type="number" class="form-control form-control-sm totalDescuentoCliente" name="clientesDescuento[<?php echo $clienteDescuentoVenta["cliente_id"] ?>][total]" id="descuentoTotal<?php echo $clienteDescuentoVenta["cliente_id"] ?>" min="0" step=".01" value="<?php echo $clienteDescuentoVenta["total"] ?>" readonly required>
                    </div>
                  </div>
                <?php endforeach; ?>
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

  $(document).ready(function(){
    $('#selectClienteDescuento').select2({});
    var zonaId = "<?php echo $zonaId ?>";
    $("#divLecturas").hide();
    $('#rubros-6').prop('readonly', false);

    mostrarSeccionesRuta();
    calcularTotalRubros();
    obtenerInventarioRuta();
    calcularCantidadVta();

    calcularTotalVta();

  });

  function mostrarSeccionesRuta() {
    let rutaId = $("#rutaVta").data('id');
    let tipoRuta = $("#rutaVta").data('tipo');

    //Cambiarán de acuerdo a los permisos por ruta
    $('#precioVta').prop('readonly', true);
    $('#lecturaInicialVta').prop('readonly', true);
    obtenerPermisosRuta(rutaId);

    //Tipo ruta pipas o venta de litros plantas-lts estación carburación lts
    if(tipoRuta == 1 || tipoRuta == 4 || tipoRuta == 5){
      $("#divLecturas").show();
      $("#cantidadVta").prop('readonly', true);
      $("#rubro-6").prop('readonly', true);
    }
    //Tipo rutas cilindreras
    else {
      $("#divLecturas").hide(); 
      $("#cantidadVta").prop('readonly', false);
      $("#rubro-6").prop('readonly', false);
    }
  }

  function obtenerInventarioRuta(){
    let rutaId = $("#rutaVta").data('id');
    let productoId = $("#productoVta").data('id');

    //Obtener inventario del producto
    $.ajax({
      data: { rutaId : rutaId, productoId: productoId },
      type: "GET",
      url: '../controller/Inventario/ObtenerInventarioRuta.php', 
      dataType: "json",
      success: function(data){
        let inventario = parseFloat(data) + parseFloat($("#totalRubrosVta").val());

        $("#inventarioVtaLabel").text("Inventario: "+parseFloat(inventario).toFixed(2)); 
        $("#inventarioVta").val(parseFloat(inventario).toFixed(2));  
      },
      error: function(data) {
        alertify.error('Ha ocurrido un error al cargar el inventario');
      }
    });
  }

  //Validar formulario
  $("#formEditarVenta").submit(function( event ) {
    let ventaId = "<?php echo $ventaId ?>";
    let rutaId = "<?php echo $venta['ruta_id'] ?>";
    let totalRubrosVta = parseFloat($("#totalRubrosVta").val());
    let cantidadVta = parseFloat($("#cantidadVta").val());
    let precioVta = parseFloat($("#precioVta").val());
    let inventarioVta = parseFloat($("#inventarioVta").val());     
    let productoId = parseFloat($("#productoVta").data('id'));
    let fechaOriginalVta = "<?php echo $venta['fecha'] ?>";
    let fechaNuevaVta = $("#fechaVta").val();
    let fechaActual = new Date("<?php echo $fechaActual ?>");

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
     //Comparar cantidad descuentos otorgados contra cantidad litros de descuentos créditos y contado
    let cantidadDescuentosCliente = 0;
    $(".cantidadDescuentoCliente").each(function(){
      cantidadDescuentosCliente += parseFloat($(this).val());
    });

      if(fechaOriginalVta != fechaNuevaVta){
        //La fecha de la venta se actualizará
        if(new Date(fechaNuevaVta) > fechaActual){
          event.preventDefault();
          alertify.error("No puedes registrar una venta en una fecha futura");
        }
        else{
        //Verificar si con la nueva fecha no hay venta posteriores
        //Si hay ventas posteriores no se puede guardar, porque afectaría directamente a las otras
          $.ajax({
            data: { 
                    ventaId : ventaId,
                    rutaId : rutaId,
                    nuevaFecha : fechaNuevaVta,
                  },
            type: "GET",
            url: '../controller/Ventas/ValidarActualizarFecha.php', 
            dataType: "json",
            success: function(data){
              if(data != 0){
                event.preventDefault();
                alertify.error('Hay ventas posteriores a esta fecha registradas, selecciona otra fecha.');
              }  
            },
            error: function(data) {
              event.preventDefault();
              alertify.error('Ha ocurrido un error al actualizar la venta.');
            }
          });
        }
      }
      else if(parseFloat(totalDescuentosCliente) != (parseFloat($("#descuentoTotalVtaContado").val()) + parseFloat($("#descuentoTotalVtaCredito").val()))){
        event.preventDefault();
        alertify.error("Coloca correctamente el desglose de los descuentos que otorgaste.");
      }
      else if(parseFloat(cantidadDescuentosCliente) != (parseFloat($("#cantidadLPVtaContado").val()) + parseFloat($("#cantidadLPVtaCredito").val()))){
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
      else if(totalRubrosVta != cantidadVta){
        event.preventDefault();
        alertify.error("El Total de Rubros tiene que ser igual a la CANTIDAD de la venta");
      }
      else if(productoId != 4 && (inventarioVta < 0.0001 || cantidadVta > inventarioVta )){
        event.preventDefault();
        alertify.error("No tienes inventario suficiente");
      }
  });

  function calcularCantidadVta(){
    let productoId = parseFloat($("#productoVta").data('id')); 
      //Producto == Litros  
      if(productoId == 4){
      let otrasSalidasVta = parseFloat($("#otrasSalidasVta").val());
      let consumoInternoVta = parseFloat($("#consumoInternoVta").val());
      let traspasosVta = parseFloat($("#traspasosVta").val());
      let pruebasVta = parseFloat($("#pruebasVta").val());

      let totalSalidasVta= otrasSalidasVta + consumoInternoVta + traspasosVta + pruebasVta;

      let lecturaFinalVta = parseFloat($("#lecturaFinalVta").val());
      let lecturaInicialVta = parseFloat($("#lecturaInicialVta").val());
      let cantidadVta = lecturaFinalVta - lecturaInicialVta;

      let cantidadRealVta = parseFloat(cantidadVta - totalSalidasVta).toFixed(2);
      $("#cantidadVta").val(cantidadRealVta);
      calcularTotalVta();
    }
  }

  //Calcular total rubros venta
  function calcularTotalRubros(){
    var totalRubros = 0;
    $(".rubros-input").each(function(){
      totalRubros = totalRubros + parseFloat($(this).val());
    });
    $("#totalRubrosVta").val(parseFloat(totalRubros).toFixed(2));
  }

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
</script>