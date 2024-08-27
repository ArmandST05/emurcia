<?php
$modelZona = new ModelZona();
$modelPrecioProducto = new ModelPrecioProducto();
$modelPedido = new ModelPedido();
$modelProducto = new ModelProducto();
$modelClientePedido = new ModelClientePedido;
$modelRuta = new ModelRuta();
date_default_timezone_set('America/Mexico_City');

$fechaInicial = (!empty($_GET['fechaInicial'])) ? $_GET['fechaInicial'] : date("Y-m-d");
$fechaFinal = (!empty($_GET['fechaFinal'])) ? $_GET['fechaFinal'] : date("Y-m-d");

if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc") {
  $zonaId = (isset($_GET['zonaId'])) ? $_GET['zonaId'] : "";
  $zona = (isset($_GET['zonaId'])) ? $_GET['zonaId'] : "";
  $zonas = $modelZona->obtenerZonasTodas();
} elseif ($_SESSION["tipoUsuario"] == "mp") { //Es un usuario multizona de captura de pedidos
  $zonas = $modelZona->obtenerZonasPorUsuario($_SESSION["id"]);
  $zonaId = (isset($_GET['zonaId'])) ? $_GET['zonaId'] : $zonas[0]["idzona"];
  $zona = (isset($_GET['zonaId'])) ? $modelZona->obtenerZonaId($zonaId)["nombre"] : $zonas[0]["nombre"];
} else {
  $zona = $_SESSION['zona'];
  $zonaId = $_SESSION['zonaId'];
}

$array_nom = array();
$array_dir = array();
$array_tel = array();
$pedidos = [];

if (!empty($zonaId)) {
  $zonaSeleccionada = $modelZona->obtenerZonaId($zonaId);
  $rutas = $modelRuta->listaPorZonaEstatus($zonaId, 1);
  $pedidos = $modelPedido->listaPedidosPorZonaEntreFechas($fechaInicial, $fechaFinal, $zonaId);
  $totalesPedidos = $modelPedido->obtenerTotalesPedidosZonaFecha($fechaInicial, $fechaFinal, $zonaId);
  $clientes = $modelClientePedido->obtenerClientesPedidosZonaId($zonaId);
  $tiposContacto = $modelPedido->listaTiposContacto();

  if ($clientes) {
    foreach ($clientes as $clienteData) {
      $lista_nombres = $clienteData['idclientepedido'] . "*" . $clienteData['nombre'];
      array_push($array_nom, $lista_nombres);
      $lista_direcciones = $clienteData['idclientepedido'] . "*" . $clienteData['direccion'];
      array_push($array_dir, $lista_direcciones);
      $lista_tel = $clienteData['idclientepedido'] . "*" . $clienteData['telefono'];
      array_push($array_tel, $lista_tel);
    }
  }
}

//Obtener todos los productos para selección
$productos = $modelProducto->listaProductosPedidos();

//Obtener productos y precios
$anio = date("Y");
$precioLts = 0;
$precioKg = 0;
$productosPrecios = [];

//Obtener precio mes actual para los litros de gas
$precioData = $modelPrecioProducto->obtenerPrecioGasZonaMes($anio, date("n"), $zonaId, 0);
if (!empty($precioData)) {
  $precioData = reset($precioData);

  $precioLts = $precioData["precio_litro"];
  $precioKg = $precioData["precio_kilo"];
}

$productoLts = [];
$productoLts["nombre"] = "Lts";
$productoLts["precio"] = $precioLts;

$productoKg = [];
$productoKg["nombre"] = "Kg";
$productoKg["precio"] = $precioKg;

array_push($productosPrecios, $productoLts, $productoKg);

//Obtener los productos de tipo cilindros
$productosCilindros = $modelProducto->obtenerCilindros();

foreach ($productosCilindros as $cilindro) {
  $productoData = [];
  $precioCilindro = 0;
  //Para los cilindros se toma el precio para el cliente asignado por el administrador. 
  //Así el administrador controla redondeo de decimales.
  $precioData = $modelProducto->obtenerPrecioMesProducto($anio, date("n"), $zonaId, 0, $cilindro["idproducto"]);

  if (!empty($precioData)) {
    $precioData = reset($precioData);
    $precioCilindro = $precioData["precio"];
  }

  $productoData["nombre"] = $cilindro["nombre"];
  $productoData["precio"] = $precioCilindro;

  array_push($productosPrecios, $productoData);

  $arrayProductosPrecios = array_chunk($productosPrecios, 3);
}
//Obtener productos y precios
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Pedidos</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Pedidos</h1>
  <?php if (!empty($zonaId) && ($_SESSION["tipoUsuario"] == "su" || $_SESSION['tipoUsuario'] == "u" || $_SESSION['tipoUsuario'] == "mp")) : ?>
    <button type="button" data-toggle="modal" data-target="#modalNuevoPedido" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">Nuevo</button>
  <?php endif; ?>
</div>
<?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "u" || $_SESSION['tipoUsuario'] == "mp") : ?>
  <!-- Content Row -->
  <div class="row">
    <!-- Lista de precios -->
    <div class="col-xl-12 col-lg-12">
      <div class="card shadow mb-4">
        <!-- Card Header -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Precios</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
          <?php foreach ($arrayProductosPrecios as $productosPreciosData) : ?>
            <div class="row">
              <?php foreach ($productosPreciosData as $productoPrecio) : ?>
                <div class="col-md-1"><?php echo $productoPrecio["nombre"] ?></div>
                <div class="col-md-1">$<?php echo number_format($productoPrecio["precio"], 2) ?></div>
              <?php endforeach; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <!-- Lista de precios -->
  </div>
<?php endif; ?>
<?php if (!empty($zonaId)) : ?>
  <!-- Content Row -->
  <div class="row">
    <!-- Lista de totales -->
    <div class="col-xl-12 col-lg-12">
      <div class="card shadow mb-4">
        <!-- Card Header -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Totales</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
          <div class="row">
            <div class="col-md-4">
              <div class="row">
                <div class="col-md-6">
                  Total Pedidos:
                </div>
                <div class="col-md-6">
                  <?php echo $totalesPedidos['total_pedidos'] ?>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="row">
                <div class="col-md-6">
                  Pedidos Atendidos:
                </div>
                <div class="col-md-6">
                  <?php echo $totalesPedidos['total_atendidos'] ?>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  Pedidos Programados:
                </div>
                <div class="col-md-6">
                  <?php echo $totalesPedidos['total_programados'] ?>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  Pedidos Cancelados:
                </div>
                <div class="col-md-6">
                  <?php echo $totalesPedidos['total_cancelados'] ?>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="row">
                <div class="col-md-6">
                  Contactados Llamadas:
                </div>
                <div class="col-md-6">
                  <?php echo $totalesPedidos['total_contacto_llamadas'] ?>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  Contactados Ruteo/Perifoneo:
                </div>
                <div class="col-md-6">
                  <?php echo $totalesPedidos['total_contacto_ruteo'] ?>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  Contactados App:
                </div>
                <div class="col-md-6">
                  <?php echo $totalesPedidos['total_contacto_app'] ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Lista de totales -->
    </div>
  </div>
<?php endif; ?>
<!-- Content Row -->
<div class="row">
  <!-- Lista de Pedidos -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Header -->
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Lista Pedidos</h6>
      </div>
      <!-- Card Body -->
      <div class="card-body">
        <form action="index.php" method="GET">
          <div class="row">
            <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "mp") : ?>
              <div class="col-md-3">
                <select class="form-control form-control-sm" name="zonaId" id="zonaId">
                  <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc") : ?>
                    <option selected disabled>Seleciona zona</option>
                  <?php endif; ?>
                  <?php foreach ($zonas as $dataZona) : ?>
                    <option value="<?php echo $dataZona['idzona'] ?>" <?php echo ($zonaId == $dataZona['idzona']) ? "selected" : "" ?>>
                      <?php echo strtoupper($dataZona["nombre"]) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            <?php endif; ?>
            <div class="col-md-2">
              <input class="form-control form-control-sm" type="date" name="fechaInicial" value="<?php echo $fechaInicial ?>">
            </div>
            <div class="col-md-2">
              <input class="form-control form-control-sm" type="date" name="fechaFinal" value="<?php echo $fechaFinal ?>">
            </div>
            <div class="col-md-1">
              <input type="hidden" name="action" value="pedidos/index.php"></input>
              <button type="submit" class="btn btn-sm btn-primary">Buscar</button>
            </div>
          </div>
        </form>
        <?php if (!empty($zonaId)) : ?>
          <hr class="my-4">
          <div class="row">
            <div class="col-md-2 offset-md-10">
              <button class="btn btn-sm btn-warning" id="btnExport"><i class="far fa-file-excel"></i> Exportar Excel</button>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12">
              <table id="listaTabla" class="table table-bordered table-sm table-responsive">
                <thead>
                  <tr>
                    <th>No.</th>
                    <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "u" || $_SESSION['tipoUsuario'] == "mp") : ?>
                      <th></th>
                    <?php endif; ?>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Avisos</th>
                    <th>Ruta</th>
                    <th>Cliente</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Fracc/Colonia</th>
                    <th>Producto</th>
                    <th>Total Kg/Lts</th>
                    <th>Folio Nota</th>
                    <th>Estatus</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($pedidos) :
                    foreach ($pedidos as $clave => $pedido) : ?>
                      <tr>
                        <td rowspan="2"><?php echo $pedido["idpedido"] ?></td>
                        <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "u" || $_SESSION['tipoUsuario'] == "mp") : ?>
                          <td rowspan="2">
                            <?php if ($pedido["estatus_pedido_id"] == 2 && $pedido["tipo_contacto_id"] != 2 && $pedido["fecha_pedido"] <= date("Y-m-d")) : ?>
                              <button class="btn btn-light btn-sm" type="button" name="<?php echo $pedido["idpedido"] ?>" id="<?php echo $pedido["idpedido"] ?>" onclick="abrirModalAvisarUnidad('<?php echo $clave; ?>')" data-toggle="tooltip" title="Avisar Unidad"><i class="fas fa-envelope"></i></button>
                            <?php endif; ?>
                            <?php if ($pedido["estatus_pedido_id"] == 2 && $pedido["fecha_pedido"] <= date("Y-m-d")) : ?>
                              <button class="btn btn-warning btn-sm" type="button" name="<?php echo $pedido["idpedido"] ?>" id="<?php echo $pedido["idpedido"] ?>" onclick="atenderPedido('<?php echo $clave ?>')" data-toggle="tooltip" title="Atender"><i class="fas fa-check-circle"></i></button>
                            <?php endif; ?>
                            <?php if ($pedido["estatus_pedido_id"] == 2) : ?>
                              <button class="btn btn-primary btn-sm" type="button" id="<?php echo $pedido["idpedido"] ?>" onclick="cancelarPedido('<?php echo $pedido['idpedido'] ?>')" data-toggle="tooltip" title="Cancelar"><i class="fas fa-ban"></i></button>
                            <?php endif; ?>
                            <?php if ($pedido["estatus_pedido_id"] == 2) : ?>
                              <button class="btn btn-secondary btn-sm" type="button" name="<?php echo $pedido["idpedido"] ?>" id="<?php echo $pedido["idpedido"] ?>" onclick="editarPedido('<?php echo $clave ?>');" data-toggle="tooltip" title="Editar"><i class="fas fa-pencil-alt"></i></button>
                            <?php endif; ?>
                          </td>
                        <?php endif; ?>
                        <td rowspan="2"><?php echo $pedido["fecha_pedido"] ?></td>
                        <td rowspan="2"><?php echo $pedido["hora_pedido"] ?></td>
                        <td rowspan="2"><?php echo $pedido["fecha_notificacion_unidad"] ?>
                          <br>
                          <h5><span class="badge <?php echo (empty($pedido['fecha_notificacion_unidad'])) ? 'badge-danger' : 'badge-light' ?>" data-toggle="tooltip" title="Mensajes Enviados"><?php echo number_format($pedido["mensajes_enviados"]) ?> <i class="fas fa-envelope"></i></span></h5>
                        </td>
                        <td rowspan="2" class="<?php echo (empty($pedido["ruta_id"])) ? 'bg-danger' : '' ?>"><b><?php echo $pedido["ruta_nombre"] ?></b><br><?php echo $pedido["vendedor_nombre"] ?></td>
                        <td rowspan="2"><b><?php echo strtoupper($pedido["cliente_nombre"]) ?></b><br><?php echo $pedido["tipo_contacto_nombre"] ?>
                        </td>
                        <td><?php echo $pedido["cliente_telefono"] ?></td>
                        <td><?php if($pedido["tipo_contacto_id"] == 3){
                          echo ($pedido["calle"]." ".$pedido["numero_exterior"]." ".$pedido["numero_interior"]); 
                        }else{
                          echo $pedido["direccion"]; 
                        }?></td>
                        <td><?php if($pedido["tipo_contacto_id"] == 3){
                          echo ($pedido["localidad_nombre"]." ".$pedido["municipio_nombre"]." ".$pedido["estado_nombre"]); 
                        }else{
                          echo $pedido["fracc_col"]; 
                        }?></td>
                        <td><?php echo $pedido["producto_nombre"] ?></td>
                        <td><?php echo $pedido["total_kg_lts"] ?></td>
                        <td><b><?php echo $pedido["folio_venta"] ?><br></b><?php echo $pedido["fecha_entrega"] ?></td>
                        <td><?php echo $pedido["estatus_pedido_nombre"] ?></td>
                      </tr>
                      <tr>
                        <td>Comentarios:</td>
                        <td colspan="6" id="comentario<?php echo $pedido["idpedido"] ?>"><?php echo $pedido["comentario"] ?></td>
                      </tr>
                  <?php endforeach;
                  endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<!-- Modal Nuevo Pedido -->
<div class="modal fade" id="modalNuevoPedido" tabindex="-1" role="dialog" aria-labelledby="modalPedido" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <form id="formNuevoPedido">
        <div class="modal-header">
          <h5 class="modal-title" id="modalPedido">Nuevo Pedido <?php echo $zonaSeleccionada["nombre"] ?><h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="cerrarNuevoPedido()">
                <span aria-hidden="true">&times;</span>
              </button>
        </div>
        <div class="modal-body nuevoPedidoForm">
          <button type="button" class="btn btn-sm btn-light" onclick="limpiarNuevoPedido()"> Limpiar <i class="fas fa-broom"></i></button>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="clienteInput">Fecha</label>
                <input class="form-control form-control-sm" type="date" id="fechaNuevo" name="fecha" min="<?php echo date("Y-m-d", strtotime('yesterday')) ?>" max="<?php echo date("Y-m-d", strtotime('tomorrow')) ?>" value="<?php echo date("Y-m-d") ?>" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="clienteInput">Tipo de Contacto</label>
                <select class="form-control form-control-sm" name="tipoContacto" id="tipoContactoNuevo" placeholder="Tipo Contacto Cliente" required>
                  <?php foreach ($tiposContacto as $tipoContacto) : ?>
                    <option value="<?php echo $tipoContacto["idtipocontactopedido"] ?>"><?php echo $tipoContacto["nombre"] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="clienteInput">Cliente</label>
                <input type="text" class="form-control form-control-sm autocomplete-input" name="clienteNombre" id="clienteNombreNuevo" placeholder="Nombre del cliente" autocomplete="off" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="clienteInput">Dirección</label>
                <input type="text" class="form-control form-control-sm autocomplete-input" name="direccion" id="direccionNuevo" placeholder="Dirección" autocomplete="off" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="clienteInput">Fraccionamiento/Colonia</label>
                <input type="text" class="form-control form-control-sm" name="colonia" id="coloniaNuevo" placeholder="Colonia" required>
              </div>
            </div>
          </div>
          <!--<div class="col-md-4">
              <div class="form-group">
                <label for="clienteInput">Calle</label>
                <input type="text" class="form-control form-control-sm autocomplete-input" name="calle" id="calleNuevo" placeholder="Calle" autocomplete="off" required>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label for="clienteInput">Número exterior</label>
                <input type="text" class="form-control form-control-sm autocomplete-input" name="numeroExterior" id="numeroExteriorNuevo" placeholder="# Exterior" autocomplete="off" required>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label for="clienteInput">Número interior</label>
                <input type="text" class="form-control form-control-sm autocomplete-input" name="numeroInterior" id="numeroInteriorNuevo" placeholder="# Interior" autocomplete="off" required>
              </div>
            </div>
          </div>-->
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="clienteInput">Teléfono (10 dígitos)</label>
                <input type="text" class="form-control form-control-sm" name="telefono" id="telefonoNuevo" placeholder="Teléfono">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="clienteInput">Producto</label>
                <select class="form-control form-control-sm" name="producto" id="productoNuevo" required>
                  <option value="0" disabled>Seleccione Opción</option>
                  <?php foreach ($productos as $producto) : ?>
                    <option value="<?php echo $producto['idproducto'] ?>"><?php echo $producto['nombre'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="clienteInput">Ruta</label>
                <select class="form-control form-control-sm" name="ruta" id="rutaNuevo" placeholder="Ruta" required>
                  <?php foreach ($rutas as $ruta) : ?>
                    <option value="<?php echo $ruta["idruta"] ?>"><?php echo $ruta["clave_ruta"] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="clienteInput">Comentarios</label>
                <textarea class="form-control form-control-sm" name="comentario" id="comentarioNuevo"></textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="alert alert-warning col-md-12" role="alert">
              Busca un cliente por su nombre, dirección o teléfono al escribir en cada campo, si haces clic en alguno de los datos que se autocompletan seleccionarás el cliente y se cargarán sus datos completos para generar el pedido.
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="clienteId" id="clienteIdNuevo" required>
          <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal" onclick="cerrarNuevoPedido()">Cancelar</button>
          <input type="submit" class="btn btn-primary btn-sm" value="Confirmar Pedido">
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Modal Nuevo Pedido -->

<!-- Modal Editar Pedido -->
<div class="modal fade" id="modalEditarPedido" tabindex="-1" role="dialog" aria-labelledby="modalPedido" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <form action="../controller/Pedidos/Actualizar.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="modalPedido">Editar Pedido <h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="limpiarEditarPedido()">
                <span aria-hidden="true">&times;</span>
              </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="clienteInput">Fecha</label>
                <input class="form-control form-control-sm" type="date" id="fechaEditar" name="fecha" min="<?php echo date("Y-m-d") ?>" max="<?php echo date("Y-m-d", strtotime('tomorrow')) ?>" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="clienteInput">Tipo de Contacto</label>
                <select class="form-control form-control-sm" name="tipoContacto" id="tipoContactoEditar" placeholder="Tipo Contacto Cliente" required>
                  <?php foreach ($tiposContacto as $tipoContacto) : ?>
                    <option value="<?php echo $tipoContacto["idtipocontactopedido"] ?>"><?php echo $tipoContacto["nombre"] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="clienteInput">Cliente</label>
                <input type="text" class="form-control form-control-sm" id="clienteEditar" readonly>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label for="clienteInput">Dirección</label>
                <input type="text" class="form-control form-control-sm" id="direccionEditar" readonly>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label for="clienteInput">Fraccionamiento/Colonia</label>
                <input type="text" class="form-control form-control-sm" id="coloniaEditar" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="clienteInput">Teléfono (10 dígitos)</label>
                <input type="text" class="form-control form-control-sm" id="telefonoEditar" readonly>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label for="clienteInput">Producto</label>
                <select class="form-control form-control-sm" name="producto" id="productoEditar" required>
                  <option value="0" disabled>Seleccione Opción</option>
                  <?php foreach ($productos as $producto) : ?>
                    <option value="<?php echo $producto['idproducto'] ?>"><?php echo $producto['nombre'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label for="clienteInput">Ruta</label>
                <select class="form-control form-control-sm" name="ruta" id="rutaEditar" required>
                  <option value="0" disabled>Seleccione Opción</option>
                  <?php foreach ($rutas as $ruta) : ?>
                    <option value="<?php echo $ruta["idruta"] ?>"><?php echo $ruta["clave_ruta"] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="clienteInput">Comentarios</label>
                <textarea class="form-control form-control-sm" name="comentario" id="comentarioEditar"></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="pedidoId" id="pedidoIdEditar" required>
          <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal" onclick="limpiarEditarPedido()">Cancelar</button>
          <input type="submit" class="btn btn-primary btn-sm" value="Actualizar Pedido" id="actualizarPedido">
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Modal Editar Pedido -->

<!-- Modal Enviar SMS -->
<div class="modal fade" id="enviarSmsUnidad" tabindex="-1" role="dialog" aria-labelledby="modalSms" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalSms">Enviar Mensaje SMS <h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-2">
            <label>Unidad:</label>
          </div>
          <div class="col-md-4">
            <label id="smsLabelUnidad"></label>
          </div>
          <div class="col-md-2">
            <label>Teléfono:</label>
          </div>
          <div class="col-md-4">
            <input type="number" class="form-control form-control-sm" name="smsTelefono" id="smsTelefono" readonly>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <textarea class="form-control form-control-sm" name="smsTexto" id="smsTexto"></textarea>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="alert alert-warning col-md-12 text-xs" role="alert">
            Si la ruta no tiene asignado un teléfono, sólo se registrará la hora en que se avisó y deberá contactarse por otro medio.
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="smsPedidoId" id="smsPedidoId">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-sm btn-primary" onclick="avisarUnidad()"><i class="fas fa-envelope"></i> Enviar SMS</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal Enviar SMS -->
<!-- Modal Atender Pedido-->
<div class="modal fade" id="modalAtenderPedido" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="../controller/Pedidos/Atender.php" method="POST" id="formAtenderPedido">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Atender Pedido</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-2">
              Pedido:
            </div>
            <div class="col-md-4">
              <label id="pedidoIdlabelAtender"></label>
            </div>
            <div class="col-md-2">
              Folio Nota:
            </div>
            <div class="col-md-4">
              <input type="text" class="form-control form-control-sm" name="folioNota" id="folioNotaAtender" required>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2">
              Cliente:
            </div>
            <div class="col-md-4">
              <label id="clienteNombreAtender"></label>
            </div>
            <div class="col-md-2">
              Total Kg/Lts:
            </div>
            <div class="col-md-4">
              <input type="text" class="form-control form-control-sm" name="totalCantidad" id="totalCantidadAtender" required>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="pedidoId" id="pedidoIdAtender" required>
          <input type="hidden" id="pedidoIndexAtender">
          <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-sm btn-primary">Atender</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Modal Atender Pedido-->
<script type="text/JavaScript">
  var pedidos = <?php echo json_encode($pedidos); ?>;

  $(document).ready(function(){
    $("#zonaId").select2({});

    var zonaId = "<?php echo $zonaId ?>";

    $("#lista_pedidos").load("lista_pedidos.php");

    //Precios por zona
    $("#zona").change(function(){
      let zona = $("#zona").val();
      window.location.href = 'index.php?action=pedidos/index.php&zona='+zona;
    });

    //Autocompletar nombre de los clientes
    var item_nom = <?= json_encode($array_nom); ?>;
    $("#clienteNombreNuevo").autocomplete({
      source: item_nom,
      select: function(event, item) {
        let clienteDatos = item.item.value;
        buscarCliente(clienteDatos);
      }
    });
    $("#clienteNombreNuevo").autocomplete("option", "appendTo", ".nuevoPedidoForm");

    //Autocompletar dirección
    var item_nom = <?= json_encode($array_dir); ?>;
    $("#direccionNuevo").autocomplete({
      source: item_nom,
      select: function(event, item) {
        let clienteDatos = item.item.value;
        buscarCliente(clienteDatos);
      }
    });
    $("#direccionNuevo").autocomplete("option", "appendTo", ".nuevoPedidoForm");

    //Autocompletar teléfono
    var item_nom = <?= json_encode($array_tel); ?>;
    $("#telefonoNuevo").autocomplete({
      source: item_nom,
      select: function(event, item) {
        let clienteDatos = item.item.value;
        buscarCliente(clienteDatos);
      }
    });
    $("#telefonoNuevo").autocomplete("option", "appendTo", ".nuevoPedidoForm");

    $('#listaTabla').DataTable({
      pageLength: 500,
      responsive: true,
      language: {
        url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
      }
    });

  });

  $("#modalNuevoPedido").modal({
    show: false,
    backdrop: 'static',
    keyboard: false
  });

  $("#formNuevoPedido").submit(function( event ) {
    //Se agregó este método para no recargar la página cuando se estén capturando pedidos de ruteo/perifoneo y agilizar el proceso.
    $.ajax({
      type: "POST",
      url: "../controller/Pedidos/Insertar.php",
      data: {
        fecha: $("#fechaNuevo").val(),
        tipoContacto: $("#tipoContactoNuevo").val(),
        clienteNombre: $("#clienteNombreNuevo").val(),
        direccion: $("#direccionNuevo").val(),
        colonia: $("#coloniaNuevo").val(),
        telefono: $("#telefonoNuevo").val(),
        producto: $("#productoNuevo").val(),
        ruta: $("#rutaNuevo").val(),
        comentario: $("#comentarioNuevo").val(),
        clienteId: $("#clienteIdNuevo").val(),
        zonaId: "<?php echo $zonaId ?>",
        zona: "<?php echo $zona ?>"
      },
      success: function(data) {
        alertify.success("Pedido registrado");
        if($("#tipoContactoNuevo").val() == 1){
          //Cliente llamó para realizar pedido
          location.reload();
        }
        else{
          //Pedido por ruteo/perifoneo
          //Limpiar sólo algunos campos
          $("#clienteNombreNuevo").val("");
          $("#clienteIdNuevo").val("");
          $("#direccionNuevo").val("");
          $("#coloniaNuevo").val("");
          $("#telefonoNuevo").val("");
          $("#comentarioNuevo").val("");
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alertify.error("Ha ocurrido un error al registrar el pedido");
      }
      
    });
    event.preventDefault();
  });

  
  function cerrarNuevoPedido() {
    if($("#tipoContactoNuevo").val() == 1){
      //Si es pedido por llamada sólo se limpia el formulario
      limpiarNuevoPedido();
    }
    else{
      //Si es pedido por ruteo/perifoneo recargar la página (ya que no se ha hecho antes) y se actualicen todos los pedidos nuevos.
      location.reload();
    }
  }

  $("#btnExport").click(function (e) {
    $("#listaTabla").btechco_excelexport({
      containerid: "listaTabla"
      , datatype: $datatype.Table
      , filename: 'pedidosZona'
    });
  });

  function limpiarNuevoPedido() {
    $("#clienteNombreNuevo").val("");
    $("#clienteIdNuevo").val("");
    $("#tipoContactoNuevo").val(1);
    $("#direccionNuevo").val("");
    $("#coloniaNuevo").val("");
    $("#telefonoNuevo").val("");
    $("#comentarioNuevo").val("");
    $("#fechaNuevo").val(new Date().toDateInputValue());
  }

  function limpiarEditarPedido() {
    $("#clienteEditar").val("");
    $("#tipoContactoEditar").val(1);
    $("#direccionEditar").val("");
    $("#coloniaEditar").val("");
    $("#telefonoEditar").val("");
    $("#comentarioEditar").val("");
    $("#productoEditar").val(0);
    $("#rutaEditar").val(0);
    $("#pedidoIdEditar").val("");
    $("#fechaEditar").val(new Date().toDateInputValue());
  }

  function limpiarAtenderPedido() {
    $("#pedidoIdAtender").val("");
    $("#pedidoIndexAtender").val("");
    $("#pedidoIdlabelAtender").text("");
    $("#clienteNombreAtender").text("");
    $("#folioNotaAtender").val("");
    $("#totalCantidadAtender").val("");
  }

  function buscarCliente(clienteDatos) {
    $.ajax({
      type: "GET",
      url: "../controller/ClientesPedidos/BuscarClientePorId.php",
      data: {
        clienteDatos: clienteDatos
      },
      success: function(data) {
        let cliente = JSON.parse(data);

        $("#clienteNombreNuevo").val(cliente["nombre"]);
        $("#direccionNuevo").val(cliente["direccion"]);
        $("#coloniaNuevo").val(cliente["colonia"]);
        $("#telefonoNuevo").val(cliente["telefono"]);
        $("#clienteIdNuevo").val(cliente["idclientepedido"]);

        var clienteZona;
        $.ajax({
          type: "GET",
          url: "../controller/Zonas/BuscarZonaNombre.php",
          data: {
            ciudad: cliente["ciudad"]
          },
          success: function(data) {
            clienteZona = data;
          }
        });
      }
    });
  }

  function limpiarAvisarUnidad() {
    $("#smsPedidoId").text();
    $("#smsLabelUnidad").text();
    $("#smsTelefono").val();
    $("#smsTexto").val();
  }

  function abrirModalAvisarUnidad(indexPedido) {
    limpiarAvisarUnidad();
    let pedido = pedidos[indexPedido];
    let fechaCortaPedido = pedido["fecha_pedido"].substring(8,10)+"-"+pedido["fecha_pedido"].substring(5,7);

    $("#smsLabelUnidad").text(pedido["ruta_nombre"]);
    $("#smsPedidoId").val(pedido["idpedido"]);
    $("#smsTelefono").val(pedido["ruta_telefono"]);
    $("#smsTexto").val(fechaCortaPedido+" "+pedido["direccion"]+" "+pedido["fracc_col"]);

    $("#enviarSmsUnidad").modal({
      show: true,
      keyboard: false,
      backdrop: 'static'
    });
  }

  function avisarUnidad() {
    let pedidoId = $("#smsPedidoId").val();
    let telefono = $("#smsTelefono").val();
    let mensaje = $("#smsTexto").val();

    if(mensaje.length < 0) alertify.success("Ingresa un mensaje válido");
    else if(telefono.trim().length > 0){
      if(telefono.length != 10) alertify.success("El teléfono debe tener 10 dígitos");
      else{
        telefono = 52+telefono;
        registrarHoraAvisoUnidad(pedidoId,2,telefono,mensaje);//2 = Aviso SMS
      }
    }else{
      registrarHoraAvisoUnidad(pedidoId,1);//1=Aviso otro
    }
  }

  function registrarHoraAvisoUnidad(pedidoId,viaInforme,telefono=null,contenidoMensaje=""){
    $.ajax({
      type: "POST",
      url: "../controller/Pedidos/AvisarRuta.php",
      data: {
        pedidoId: pedidoId,
        viaInforme: viaInforme,
        telefono: telefono,
        contenido: contenidoMensaje
      },
      success: function(data) {
        alertify.success("Hora de Aviso Registrada");
        window.location.href = 'index.php?action=pedidos/index.php';
      },      
      error: function() {
        alertify.error("No se pudo avisar a la unidad");
      }
    });
  }

  function editarPedido(indexPedido) {
    let pedido = pedidos[indexPedido];
    $("#pedidoIdEditar").val(pedido["idpedido"]);
    $("#clienteEditar").val(pedido["cliente_nombre"]);
    if(pedido["tipo_contacto_id"]) $("#tipoContactoEditar").val(pedido["tipo_contacto_id"]);
    else $("#tipoContactoEditar").val(1);
    $("#fechaEditar").val(pedido["fecha_pedido"]);
    $("#direccionEditar").val(pedido["direccion"]);
    $("#coloniaEditar").val(pedido["fracc_col"]);
    $("#telefonoEditar").val(pedido["telefono"]);
    $("#comentarioEditar").val(pedido["comentario"]);
    $("#productoEditar").val(pedido["producto_id"]);
    $("#rutaEditar").val(pedido["ruta_id"]);

    $("#modalEditarPedido").modal({
      show: true,
      keyboard: false,
      backdrop: 'static'
    });
  }

  function atenderPedido(indexPedido) {
    limpiarAtenderPedido();
    let pedido = pedidos[indexPedido];

    $("#pedidoIdAtender").val(pedido["idpedido"]);
    $("#pedidoIndexAtender").val(indexPedido);
    $("#pedidoIdlabelAtender").text(""+pedido["idpedido"]);
    $("#clienteNombreAtender").text(pedido["cliente_nombre"]);

    $("#modalAtenderPedido").modal({
      show: true,
      keyboard: false,
      backdrop: 'static'
    });
  }

  function cancelarPedido(pedidoId) {
    alertify.confirm("¿Realmente desea cancelar el pedido?",
      function() {
        let zona = "<?php echo $zona ?>";
        $.ajax({
          type: "POST",
          url: "../controller/Pedidos/CancelarPedido.php",
          data: {
            pedidoId: pedidoId
          },
          success: function(data) {
            alertify.success("Pedido Cancelado");
            location.reload();
          }
        });
      },
      function() {
      })
      .set({
        title: "Cancelar Pedido"
      })
      .set({
        labels: {
          ok: 'Aceptar',
          cancel: 'Cancelar'
        }
      });
  }

    //Validar que al Atender Pedido ya se avisara a la unidad
    $("#formAtenderPedido").submit(function(event) {
      let indexPedido = $("#pedidoIndexAtender").val();
      let pedido = pedidos[indexPedido];
      let avisoUnidad = pedido["fecha_notificacion_unidad"];
      let tipoContacto = pedido["tipo_contacto_id"];
      //Tipo de contacto Perifoneo/Ruteo (2) se atiende el pedido por defecto.
      if ((tipoContacto == 1 && avisoUnidad) || tipoContacto == 2) return true;
      else {
        event.preventDefault();
        alertify.error("Avisa a la Unidad que atenderá el pedido");
      }
  });
</script>