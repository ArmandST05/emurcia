<?php
date_default_timezone_set('America/Mexico_City');
//require('/session1.php');
$zona = $_SESSION['zona'];
if (isset($_GET["action"])) $action = $_GET["action"];
elseif ($_SESSION['tipoUsuario'] == "mv") $action = "ventas/index.php";
elseif ($_SESSION["tipoUsuario"] == "no") $action = "nominas/index.php";
elseif ($_SESSION['tipoZona'] == 2) $action = "creditosgasolina/index.php";
else $action = "pedidos/index.php";

//Importar modelos
include_once('../model/ModelAutoconsumo.php');
include_once('../model/ModelCompra.php');
include_once('../model/ModelCompraGasolina.php');
include_once('../model/ModelCliente.php');
include_once('../model/ModelClienteDescuento.php');
include_once("../model/ModelClientePedido.php");
include_once('../model/ModelCompania.php');
include_once('../model/ModelCredito.php');
include_once('../model/ModelCategoriaGasto.php');
include_once('../model/ModelConceptoGasto.php');
include_once('../model/ModelDesviacionVolumen.php');
include_once('../model/ModelDonacion.php');
include_once('../model/ModelDescuentoDeposito.php');
include_once('../model/ModelDescuento.php');
include_once('../model/ModelEstado.php');
include_once('../model/ModelGasto.php');
include_once('../model/ModelInventario.php');
include_once('../model/ModelLogin.php');
include_once('../model/ModelLocalidad.php');
include_once('../model/ModelMunicipio.php');
include_once('../model/ModelOrigenGasto.php');
include_once('../model/ModelProducto.php');
include_once('../model/ModelPedido.php');
include_once('../model/ModelPermiso.php');
include_once('../model/ModelProveedor.php');
include_once('../model/ModelPresupuesto.php');
include_once('../model/ModelPresupuestoConcepto.php');
include_once('../model/ModelPrecioProducto.php');
include_once('../model/ModelReporte.php');
include_once('../model/ModelRubroVenta.php');
include_once('../model/ModelRuta.php');
include_once('../model/ModelTraspaso.php');
include_once('../model/ModelVenta.php');
include_once('../model/ModelVentaGasolina.php');
include_once('../model/ModelZona.php');
include_once('../model/ModelSmsEnviado.php');
include_once('../model/ModelEmpleado.php');
include_once('../model/ModelMeta.php');
include_once('../model/ModelNomina.php');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Power Gas</title>

    <!-- Custom fonts for this template-->
    <link href="../plugins/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- JQuery -->
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/jquery-1.12.1.min.js"></script>
    <script src="../js/jquery-easing/jquery.easing.min.js"></script>

    <script src="../js/jquery.btechco.excelexport.js"></script>
    <script src="../js/jquery.base64.js"></script>

    <link rel="stylesheet" type="text/css" href="../js/jquery-ui.css">
    <script type="text/javascript" src="../js/jquery-ui.js"></script>
    <!-- JQuery -->

    <!-- TreeTable -->
    <link href="../plugins/treetable/jquery.treetable.css" rel="stylesheet" />
    <link href="../plugins/treetable/jquery.treetable.theme.default.css" rel="stylesheet" />
    <script src="../plugins/treetable/jquery.treetable.js"></script>
    <!-- TreeTable -->

    <!-- Select2 -->
    <link href="../plugins/select2/select2.min.css" rel="stylesheet" />
    <script src="../plugins/select2/select2.min.js"></script>
    <script src="../plugins/select2/i18n/es.js"></script>
    <!-- Select2 -->

    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="../plugins/bootstrap/css/bootstrap.min.css" />
    <script type="text/javascript" src="../plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- Bootstrap -->

    <!-- Sb-admin -->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <!-- Sb-admin -->


    <!--Data-Tables -->
    <link rel="stylesheet" type="text/css" href="../plugins/datatables/datatables.min.css" />
    <script type="text/javascript" src="../plugins/datatables/datatables.min.js"></script>
    <!--Data-Tables -->

    <!-- Fixed Columns -->
    <link rel="stylesheet" src="../plugins/datatables/FixedColumns-3.3.1/css/fixedColumns.bootstrap.min.css">
    <script src="../plugins/datatables/FixedColumns-3.3.1/js/dataTables.fixedColumns.min.js"></script>
    <!-- Fixed Columns -->

    <!-- AlertifyJs-->
    <!-- include the script -->
    <script src="../plugins/alertifyjs/alertify.min.js"></script>
    <!-- include the style -->
    <link rel="stylesheet" href="../plugins/alertifyjs/alertify.min.css" />
    <!-- include a theme -->
    <link rel="stylesheet" href="../plugins/alertifyjs/themes/default.min.css" />
    <!-- AlertifyJs-->
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar" style="position: sticky; top: 0px;">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-text mx-3"><img Style="width:100px;" src="../images/emurcia.png"></div>
            </a>
            <!-- Divider -->
            <hr class="sidebar-divider">
            <?php if ($_SESSION['tipoUsuario'] == "su" || $_SESSION["tipoUsuario"] == "uc") : ?>
                <!-- Nav Item - Créditos Collapse Menu -->
                <li class="nav-item active">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePedidos" aria-expanded="true" aria-controls="collapsePedidos">
                        <span>Pedidos</span>
                    </a>
                    <div id="collapsePedidos" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                        <div class="bg-transparent py-2 collapse-inner rounded">
                            <a class="collapse-item" href="index.php?action=pedidos/index.php">Pedidos</a>
                            <a class="collapse-item" href="index.php?action=pedidos/proximos_pedidos.php">Próximos Pedidos</a>
                            <?php if ($_SESSION['tipoUsuario'] == "su" || $_SESSION['tipoUsuario'] == "uc") : ?>
                                <a class="collapse-item" href="index.php?action=reportes/sms_enviados.php">Reporte SMS</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </li>
                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Nav Item - Clientes Collapse Menu -->
                <li class="nav-item active">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseClientes" aria-expanded="true" aria-controls="collapseUtilities">
                        <span>Clientes</span>
                    </a>
                    <div id="collapseClientes" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                        <div class="bg-transparent py-2 collapse-inner rounded">
                            <a class="collapse-item" href="index.php?action=clientes/index_pedido.php">Clientes pedidos</a>
                            <a class="collapse-item" href="index.php?action=clientes/index_credito.php">Clientes crédito</a>
                            <a class="collapse-item" href="index.php?action=clientesdescuento/index.php">Clientes descuento</a>
                        </div>
                    </div>
                </li>
                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Nav Item - Créditos Collapse Menu -->
                <li class="nav-item active">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCreditos" aria-expanded="true" aria-controls="collapseUtilities">
                        <span>Créditos</span>
                    </a>
                    <div id="collapseCreditos" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                        <div class="bg-transparent py-2 collapse-inner rounded">
                            <a class="collapse-item" href="index.php?action=creditos/index.php">Gas</a>
                            <a class="collapse-item" href="index.php?action=creditosgasolina/index.php">Gasolina</a>
                        </div>
                    </div>
                </li>

                <!-- Divider -->

                <hr class="sidebar-divider">

                <!-- Nav Item - Créditos Collapse Menu -->
                <li class="nav-item active">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseGastos" aria-expanded="true" aria-controls="collapseUtilities">
                        <span>Gastos</span>
                    </a>
                    <div id="collapseGastos" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                        <div class="bg-transparent py-2 collapse-inner rounded">
                            <?php if ($_SESSION['tipoUsuario'] == "su") : ?>
                                <a class="collapse-item" href="index.php?action=gastosconfiguracion/index.php">Configuración</a>
                            <?php endif; ?>
                            <a class="collapse-item" href="index.php?action=gastosadministrativos/index.php">Gastos Administrativos</a>
                            <a class="collapse-item" href="index.php?action=gastosruta/index.php">Gastos Punto Venta</a>
                            <a class="collapse-item" href="index.php?action=presupuestosconceptos/index.php">Presupuesto Conceptos</a>
                        </div>
                    </div>
                </li>
                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Nav Item - Inventario Collapse Menu -->
                <li class="nav-item active">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseInventarioGas" aria-expanded="true" aria-controls="collapseUtilities">
                        <span>Inventario</span>
                    </a>
                    <div id="collapseInventarioGas" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                        <div class="bg-transparent py-2 collapse-inner rounded">
                            <a class="collapse-item" href="index.php?action=inventario/index_rutas.php">Inventario Gas</a>
                            <a class="collapse-item" href="index.php?action=inventario/index_gasolina.php">Inventario Gasolina</a>
                            <a class="collapse-item" href="index.php?action=inventario/index_teorico.php">Inventario Teórico</a>
                            <a class="collapse-item" href="index.php?action=autoconsumos/index.php">Autoconsumos</a>
                            <a class="collapse-item" href="index.php?action=donaciones/index.php">Donaciones</a>
                            <a class="collapse-item" href="index.php?action=desviacionvolumen/index.php">Gestión de Medición</a>
                            <a class="collapse-item" href="index.php?action=traspasos/index.php">Traspasos</a>
                        </div>
                    </div>
                </li>
                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Nav Item - Inventario Collapse Menu -->
                <li class="nav-item active">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseVentas" aria-expanded="true" aria-controls="collapseUtilities">
                        <span>Ventas</span>
                    </a>
                    <div id="collapseVentas" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                        <div class="bg-transparent py-2 collapse-inner rounded">
                            <a class="collapse-item" href="index.php?action=ventas/ventas_cliente.php">Ventas por Cliente</a>
                            <a class="collapse-item" href="index.php?action=ventas/index.php">Ventas Gas</a>
                            <a class="collapse-item" href="index.php?action=preciosproductos/capturar_precio_mes_gas.php">Precio Gas</a>
                            <a class="collapse-item" href="index.php?action=ventasgasolina/index.php">Ventas Gasolina</a>
                            <a class="collapse-item" href="index.php?action=preciosproductos/capturar_precio_mes_gasolina.php"><span>Precio Gasolina</span></a>
                            <a class="collapse-item" href="index.php?action=descuentosdeposito/index.php">Detallado Depósitos</a>
                            <a class="collapse-item" href="index.php?action=clientesdescuento/reporte.php">Reporte Descuentos</a>
                            <a class="collapse-item" href="index.php?action=ventas/reporte_f.php">Reporte F</a>
                        </div>
                    </div>
                </li>
                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Nav Item - Compras Collapse Menu -->
                <li class="nav-item active">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCompras" aria-expanded="true" aria-controls="collapseUtilities">
                        <span>Compras</span>
                    </a>
                    <div id="collapseCompras" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                        <div class="bg-transparent py-2 collapse-inner rounded">
                            <a class="collapse-item" href="index.php?action=comprasgas/index.php">Gas</a>
                            <a class="collapse-item" href="index.php?action=comprasgasolina/index.php">Gasolina</a>
                        </div>
                    </div>
                </li>
                <!-- Divider -->
                <hr class="sidebar-divider">
                <!-- Nav Item - Collapse Menu -->
                <li class="nav-item active">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePuntos" aria-expanded="true" aria-controls="collapseUtilities">
                        <span>Sistema puntos</span>
                    </a>
                    <div id="collapsePuntos" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                        <div class="bg-transparent py-2 collapse-inner rounded">
                            <a class="collapse-item" href="index.php?action=clientes-puntos/index.php">Clientes puntos</a>
                            <a class="collapse-item" href="index.php?action=sistema-puntos/capturar-valor-puntos.php">Puntos por zona</a>
                            <a class="collapse-item" href="index.php?action=sistema-puntos/reporte-canjeados.php">Reporte puntos canjeados</a>
                            <!--<a class="collapse-item" href="index.php?action=sistema-puntos/configuracion.php">Configuración</a>-->
                        </div>
                    </div>
                </li>
                <!-- Divider -->
                <hr class="sidebar-divider">

                <li class="nav-item active">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseNomina" aria-expanded="true" aria-controls="collapseNomina">
                        <span>Nómina</span>
                    </a>
                    <div id="collapseNomina" class="collapse" aria-labelledby="headingNomina" data-parent="#accordionSidebar">
                        <div class="bg-transparent py-2 collapse-inner rounded">
                            <a class="collapse-item" href="index.php?action=nominas/index.php">Nómina</a>
                            <a class="collapse-item" href="index.php?action=nominas/fondo.php">Fondos de ahorro</a>
                            <a class="collapse-item" href="index.php?action=metas/index.php">Metas</a>
                        </div>
                    </div>
                </li>
                <!-- Divider -->
                <hr class="sidebar-divider">
                <!-- Nav Item - Dashboard -->
                <li class="nav-item active">
                    <a class="nav-link" href="index.php?action=rutas/index.php">
                        <span>Rutas</span></a>
                </li>
                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Nav Item - Compras Collapse Menu -->
                <li class="nav-item active">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAdministracion" aria-expanded="true" aria-controls="collapseUtilities">
                        <span>Configuración</span>
                    </a>
                    <div id="collapseAdministracion" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                        <div class="bg-transparent py-2 collapse-inner rounded">
                            <a class="collapse-item" href="index.php?action=usuarios/index.php">Usuarios</a>
                            <a class="collapse-item" href="index.php?action=empleados/index.php">Empleados</a>
                        </div>
                    </div>
                </li>

            <?php elseif ($_SESSION['tipoUsuario'] == "no") : ?>
                <!-- Nav Item - Dashboard -->
                <li class="nav-item active">
                    <a class="nav-link" href="index.php?action=nominas/index.php">
                        <span>Nómina</span></a>
                </li>
                <!-- Divider -->
                <hr class="sidebar-divider">
                <!-- Nav Item - Dashboard -->
                <li class="nav-item active">
                    <a class="nav-link" href="index.php?action=metas/index.php">
                        <span>Metas</span></a>
                </li>
                <!-- Divider -->
                <hr class="sidebar-divider">
                <!-- Nav Item - Dashboard -->
                <li class="nav-item active">
                    <a class="nav-link" href="index.php?action=rutas/index.php">
                        <span>Rutas</span></a>
                </li>
                <!-- Divider -->
                <hr class="sidebar-divider">
                <!-- Nav Item - Collapse Menu -->

                <!-- Nav Item - Dashboard -->
                <li class="nav-item active">
                    <a class="nav-link" href="index.php?action=empleados/index.php">
                        <span>Empleados</span></a>
                </li>

                <!-- Divider -->
                <hr class="sidebar-divider">
            <?php elseif ($_SESSION['tipoUsuario'] == "mv") : //Usuario captura ventas gas multizona
            ?>
                <li class="nav-item active">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseVentas" aria-expanded="true" aria-controls="collapseUtilities">
                        <span>Ventas</span>
                    </a>
                    <div id="collapseVentas" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                        <div class="bg-transparent py-2 collapse-inner rounded">
                            
                            <a class="collapse-item" href="index.php?action=ventas/index.php">Ventas</a>
                            <a class="collapse-item" href="index.php?action=descuentosdeposito/index.php">Descuentos Depósito</a>
                            <a class="collapse-item" href="index.php?action=ventas/reporte_f.php">Reporte F</a>
                        </div>
                    </div>
                </li>
            <?php elseif ($_SESSION['tipoUsuario'] == "mp") : //Usuario captura pedidos gas multizona 
            ?>
                <!-- Divider -->
                <li class="nav-item active">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePedidos" aria-expanded="true" aria-controls="collapsePedidos">
                        <span>Pedidos</span>
                    </a>
                    <div id="collapsePedidos" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                        <div class="bg-transparent py-2 collapse-inner rounded">
                            <a class="collapse-item" href="index.php?action=pedidos/index.php">Pedidos</a>
                            <a class="collapse-item" href="index.php?action=pedidos/proximos_pedidos.php">Próximos Pedidos</a>
                        </div>
                    </div>
                </li>
            <?php elseif ($_SESSION['tipoUsuario'] == "ga") : ?>

                <!-- Divider -->

                <hr class="sidebar-divider">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php?action=gastosadministrativos/index.php">
                        <span>Gastos Administrativos</span></a>
                </li>
                <hr class="sidebar-divider">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php?action=gastosruta/index.php">
                        <span>Gastos Punto Venta</span></a>
                </li>
                <hr class="sidebar-divider">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php?action=presupuestosconceptos/index.php">
                        <span>Presupuesto Conceptos</span></a>
                </li>
                <!-- Divider -->
                <hr class="sidebar-divider">

            <?php elseif ($_SESSION['tipoUsuario'] == "inv") : ?>
                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Nav Item - Inventario Collapse Menu -->
                <li class="nav-item active">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseInventarioGas" aria-expanded="true" aria-controls="collapseUtilities">
                        <span>Inventario</span>
                    </a>
                    <div id="collapseInventarioGas" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                        <div class="bg-transparent py-2 collapse-inner rounded">
                            <a class="collapse-item" href="index.php?action=inventario/index_rutas.php">Inventario Gas</a>
                            <a class="collapse-item" href="index.php?action=inventario/index_gasolina.php">Inventario Gasolina</a>
                            <a class="collapse-item" href="index.php?action=autoconsumos/index.php">Autoconsumos</a>
                            <a class="collapse-item" href="index.php?action=donaciones/index.php">Donaciones</a>
                            <a class="collapse-item" href="index.php?action=desviacionvolumen/index.php">Gestión de Medición</a>
                            <a class="collapse-item" href="index.php?action=traspasos/index.php">Traspasos</a>
                        </div>
                    </div>
                </li>

                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Nav Item - Créditos Collapse Menu -->
                <li class="nav-item active">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseGastos" aria-expanded="true" aria-controls="collapseUtilities">
                        <span>Gastos</span>
                    </a>
                    <div id="collapseGastos" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                        <div class="bg-transparent py-2 collapse-inner rounded">
                            <?php if ($_SESSION['tipoUsuario'] == "su") : ?>
                                <a class="collapse-item" href="index.php?action=gastosconfiguracion/index.php">Configuración</a>
                            <?php endif; ?>
                            <a class="collapse-item" href="index.php?action=gastosadministrativos/index.php">Gastos Administrativos</a>
                            <a class="collapse-item" href="index.php?action=gastosruta/index.php">Gastos Punto Venta</a>
                            <a class="collapse-item" href="index.php?action=presupuestosconceptos/index.php">Presupuesto Conceptos</a>
                        </div>
                    </div>
                </li>
                
                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Nav Item - Inventario Collapse Menu -->
                <li class="nav-item active">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseVentas" aria-expanded="true" aria-controls="collapseUtilities">
                        <span>Ventas</span>
                    </a>
                    <div id="collapseVentas" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                        <div class="bg-transparent py-2 collapse-inner rounded">
                            <a class="collapse-item" href="index.php?action=ventas/reporte_f.php">Reporte F</a>
                        </div>
                    </div>
                </li>
                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Nav Item - Compras Collapse Menu -->
                <li class="nav-item active">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCompras" aria-expanded="true" aria-controls="collapseUtilities">
                        <span>Compras</span>
                    </a>
                    <div id="collapseCompras" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                        <div class="bg-transparent py-2 collapse-inner rounded">
                            <a class="collapse-item" href="index.php?action=comprasgas/index.php">Gas</a>
                            <a class="collapse-item" href="index.php?action=comprasgasolina/index.php">Gasolina</a>
                        </div>
                    </div>
                </li>

            <?php elseif ($_SESSION['tipoZona'] == 1) : ?>
                <!-- Nav Item - Créditos Collapse Menu -->
                <li class="nav-item active">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePedidos" aria-expanded="true" aria-controls="collapsePedidos">
                        <span>Pedidos</span>
                    </a>
                    <div id="collapsePedidos" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                        <div class="bg-transparent py-2 collapse-inner rounded">
                            <a class="collapse-item" href="index.php?action=pedidos/index.php">Pedidos</a>
                            <a class="collapse-item" href="index.php?action=pedidos/proximos_pedidos.php">Próximos Pedidos</a>
                        </div>
                    </div>
                </li>
                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Nav Item - Clientes Collapse Menu -->
                <li class="nav-item active">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseClientes" aria-expanded="true" aria-controls="collapseUtilities">
                        <span>Clientes</span>
                    </a>
                    <div id="collapseClientes" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                        <div class="bg-transparent py-2 collapse-inner rounded">
                            <a class="collapse-item" href="index.php?action=clientes/index_pedido.php">Clientes pedidos</a>
                            <a class="collapse-item" href="index.php?action=clientes/index_credito.php">Clientes crédito</a>
                        </div>
                    </div>
                </li>
                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Nav Item - Precio Mes Gas -->
                <li class="nav-item active">
                    <a class="nav-link" href="index.php?action=creditos/index.php">
                        <span>Créditos</span></a>
                </li>
                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Nav Item - Créditos Collapse Menu -->
                <li class="nav-item active">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseGastos" aria-expanded="true" aria-controls="collapseUtilities">
                        <span>Gastos</span>
                    </a>
                    <div id="collapseGastos" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                        <div class="bg-transparent py-2 collapse-inner rounded">
                            <a class="collapse-item" href="index.php?action=gastosadministrativos/index.php">Gastos Administrativos</a>
                            <a class="collapse-item" href="index.php?action=gastosruta/index.php">Gastos Punto Venta</a>
                            <a class="collapse-item" href="index.php?action=presupuestosconceptos/index.php">Presupuesto Conceptos</a>
                        </div>
                    </div>
                </li>
                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Nav Item - Inventario Collapse Menu -->
                <li class="nav-item active">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseInventarioGas" aria-expanded="true" aria-controls="collapseUtilities">
                        <span>Inventario</span>
                    </a>
                    <div id="collapseInventarioGas" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                        <div class="bg-transparent py-2 collapse-inner rounded">
                            <a class="collapse-item" href="index.php?action=inventario/index_rutas.php">Inventario</a>
                            <a class="collapse-item" href="index.php?action=autoconsumos/index.php">Autoconsumos</a>
                            <a class="collapse-item" href="index.php?action=donaciones/index.php">Donaciones</a>
                            <a class="collapse-item" href="index.php?action=desviacionvolumen/index.php">Gestión de Medición</a>
                            <a class="collapse-item" href="index.php?action=traspasos/index.php">Traspasos</a>
                        </div>
                    </div>
                </li>
                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Nav Item - Inventario Collapse Menu -->
                <li class="nav-item active">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseVentas" aria-expanded="true" aria-controls="collapseUtilities">
                        <span>Ventas</span>
                    </a>
                    <div id="collapseVentas" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                        <div class="bg-transparent py-2 collapse-inner rounded">

                        <a class="collapse-item" href="index.php?action=ventas/ventas_cliente.php">Ventas por Cliente</a>

                            <a class="collapse-item" href="index.php?action=ventas/index.php">Ventas</a>
                            <a class="collapse-item" href="index.php?action=descuentosdeposito/index.php">Descuentos Depósito</a>
                            <a class="collapse-item" href="index.php?action=ventas/reporte_f.php">Reporte F</a>
                        </div>
                    </div>
                </li>

                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Nav Item - Precio Mes Gas -->
                <li class="nav-item active">
                    <a class="nav-link" href="index.php?action=comprasgas/index.php">
                        <span>Compras</span></a>
                </li>

                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Nav Item - Dashboard -->
                <li class="nav-item active">
                    <a class="nav-link" href="index.php?action=rutas/index.php">
                        <span>Rutas</span></a>
                </li>

            <?php elseif ($_SESSION['tipoZona'] == 2) : ?>
                <!-- Nav Item  -->
                <li class="nav-item active">
                    <a class="nav-link" href="index.php?action=clientes/index_credito.php">
                        <span>Clientes</span></a>
                </li>
                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Nav Item - Precio Mes Gas -->
                <li class="nav-item active">
                    <a class="nav-link" href="index.php?action=creditosgasolina/index.php">
                        <span>Créditos</span></a>
                </li>
                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Nav Item - Créditos Collapse Menu -->
                <li class="nav-item active">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseGastos" aria-expanded="true" aria-controls="collapseUtilities">
                        <span>Gastos</span>
                    </a>
                    <div id="collapseGastos" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                        <div class="bg-transparent py-2 collapse-inner rounded">
                            <a class="collapse-item" href="index.php?action=gastosadministrativos/index.php">Gastos Administrativos</a>
                            <a class="collapse-item" href="index.php?action=gastosruta/index.php">Gastos Punto Venta</a>
                            <a class="collapse-item" href="index.php?action=presupuestosconceptos/index.php">Presupuesto Conceptos</a>
                        </div>
                    </div>
                </li>
                <!-- Divider -->
                <hr class="sidebar-divider">

                <li class="nav-item active">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseInventario" aria-expanded="true" aria-controls="collapseUtilities">
                        <span>Inventario</span>
                    </a>
                    <div id="collapseInventario" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                        <div class="bg-transparent py-2 collapse-inner rounded">
                            <a class="collapse-item" href="index.php?action=inventario/index_gasolina.php">Inventario</a>
                            <a class="collapse-item" href="index.php?action=desviacionvolumen/index.php">Gestión de medición</a>
                        </div>
                    </div>
                </li>
                <hr class="sidebar-divider">

                <!-- Nav Item - Precio Mes Gas -->
                <li class="nav-item active">
                    <a class="nav-link" href="index.php?action=ventasgasolina/index.php">
                        <span>Ventas</span></a>
                </li>
                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Nav Item - Precio Mes Gas -->
                <li class="nav-item active">
                    <a class="nav-link" href="index.php?action=comprasgasolina/index.php">
                        <span>Compras</span></a>
                </li>

                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Nav Item - Precio Mes Gasolina -->
                <li class="nav-item active">
                    <a class="nav-link" href="index.php?action=preciosproductos/capturar_precio_mes_gasolina.php">
                        <span>Precio Mes</span></a>
                </li>

            <?php endif; ?>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <!-- Nav Item - User Information -->
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span class="mr-2 d-none d-lg-inline text-gray-600"><?php echo $_SESSION['user'] ?></span>
                            </a>
                        </li>
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item">
                            <a class="nav-link" onclick="cerrarSesion()"><span class="mr-2 d-none d-lg-inline text-gray-600">Cerrar sesión</span></a>
                        </li>
                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid" id="contenido">
                    <?php include $action; ?>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Techno Consulting</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="../js/sb-admin-2.min.js"></script>

</body>

</html>

<style>
    .sidebar .nav-item .nav-link {
        padding: .1rem !important;
        color: #990000 !important;
    }
</style>
<script>
    //Inicializar librería select2 en español
    $.fn.select2.defaults.set('language', 'es');

    $(document).ready(function() {
        mostrarMensaje();
    });

    function mostrarMensaje() {
        var tipo = "<?php echo $_SESSION['alerta-tipo']; ?>";
        var mensaje = "<?php echo $_SESSION['alerta-mensaje']; ?>";

        if (tipo != "null" && mensaje != "null") {
            if (tipo == "success") alertify.success(mensaje);
            else if (tipo == "error") alertify.error(mensaje);
            else if (tipo == "warning") alertify.warning(mensaje);
        }
    }

    function cerrarSesion() {
        alertify.confirm("¿Realmente deseas cerrar sesión? Se perderán los cambios no guardados.",
                function() {
                    window.location.href = 'cerrarsesion.php';
                },
                function() {})
            .set({
                title: "Cerrar sesión"
            })
            .set({
                labels: {
                    ok: 'Aceptar',
                    cancel: 'Cancelar'
                }
            });
    }
</script>