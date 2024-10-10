<?php
// Crear la instancia de ModelVenta y ModelZona
$modelVenta = new ModelVenta();
$ModelZonas = new ModelZona();

// Obtener la lista de zonas
$zonas = $ModelZonas->obtenerZonasTodas();

// Definir fechas: si no se proporcionan, las dejamos vacías
$fechaInicial = isset($_POST["fechaInicial"]) ? $_POST["fechaInicial"] : "";
$fechaFinal = isset($_POST["fechaFinal"]) ? $_POST["fechaFinal"] : "";

// Manejar la selección de una zona, cliente y tipo de consulta (Ventas o Pedidos)
$ventas = [];
$pedidos = [];
$zonaId = isset($_POST['zona']) ? $_POST['zona'] : null; // Guardar el ID de la zona seleccionada
$clienteId = isset($_POST['cliente_id']) && is_numeric($_POST['cliente_id']) ? $_POST['cliente_id'] : null;
$tipoConsulta = isset($_POST['tipo_consulta']) ? $_POST['tipo_consulta'] : 'ventas'; // Por defecto, "Ventas"

// Si se seleccionó una zona, obtener los clientes de esa zona según el tipo de consulta
$clientesVentas = [];
$clientesPedidos = [];

// Si se seleccionó una zona, obtener los clientes de ventas y pedidos de esa zona
if ($zonaId) {
    $clientesVentas = $modelVenta->obtenerClientesVentas($zonaId); // Clientes de ventas
    $clientesPedidos = $modelVenta->obtenerClientesPedidos($zonaId); // Clientes de pedidos
}

// Si se seleccionó un cliente, obtener las ventas o pedidos según la opción seleccionada
if ($clienteId) {
    if ($tipoConsulta == 'ventas') {
        // Obtener ventas del cliente
        $ventas = $modelVenta->obtenerVentasPorCliente($clienteId, $fechaInicial, $fechaFinal);
        
    } else if ($tipoConsulta == 'pedidos') {
        // Obtener pedidos del cliente
        $pedidos = $modelVenta->obtenerPedidosPorCliente($clienteId, $fechaInicial, $fechaFinal);
       
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas por Cliente</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
            font-size: 0.9em;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<div class="row">
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Buscar</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="row">
                <div class="col-md-4">
                        <div class="form-group">
                            <label>Tipo de Consulta:</label>
                            <select class="form-control form-control-sm" name="tipo_consulta" id="tipo_consulta" required onchange="this.form.submit();">
                                <option value="ventas" <?php echo $tipoConsulta == 'ventas' ? 'selected' : ''; ?>>Clientes Ventas</option>
                                <option value="pedidos" <?php echo $tipoConsulta == 'pedidos' ? 'selected' : ''; ?>>Clientes Pedidos</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Zona:</label>
                            <select class="form-control form-control-sm" name="zona" id="zona" required onchange="this.form.submit();">
                                <option value="">--Selecciona una zona--</option>
                                <?php foreach ($zonas as $zona): ?>
                                    <option value="<?php echo $zona['idzona']?>" 
                                        <?php echo isset($zonaId) && $zonaId == $zona['idzona'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($zona['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
    <div class="form-group">
        <label>Cliente:</label>
        <select class="form-control form-control-sm" name="cliente_id" id="cliente" required <?php echo $zonaId ? '' : 'disabled'; ?>>
    <option value="">--Selecciona un cliente--</option>
    <?php 
    // Mostrar clientes dependiendo del tipo de consulta
    if ($tipoConsulta == 'ventas') {
        foreach ($clientesVentas as $cliente): 
            ?>
            <option value="<?php echo htmlspecialchars($cliente['idclientedescuento']); ?>" 
                <?php echo isset($clienteId) && $clienteId == $cliente['idclientedescuento'] ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($cliente['nombre']); ?>
            </option>
            <?php 
        endforeach; 
    } else if ($tipoConsulta == 'pedidos') {
        foreach ($clientesPedidos as $cliente): 
            ?>
            <option value="<?php echo htmlspecialchars($cliente['idclientepedido']); ?>" 
                <?php echo isset($clienteId) && $clienteId == $cliente['idclientepedido'] ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($cliente['nombre']); ?>
            </option>
            <?php 
        endforeach;
    }
    ?>
</select>

    </div>
</div>

                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Desde:</label>
                            <input class="form-control form-control-sm" type="date" id="fechaInicial" name="fechaInicial" value="<?php echo $fechaInicial; ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Hasta:</label>
                            <input class="form-control form-control-sm" type="date" id="fechaFinal" name="fechaFinal" value="<?php echo $fechaFinal; ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 pull-right">
                        <input class="btn btn-primary btn-sm" type='submit' value='Mostrar Resultados'>
                    </div>
                </div>
            </form>
        </div>
    </div>
  </div>
</div>

<!-- Mostrar resultados según la consulta seleccionada -->
<?php if (!empty($ventas) && $tipoConsulta == 'ventas'): ?>
    <h2>Ventas del cliente seleccionado:</h2>
    <table>
        <tr>
            <th>Cliente</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Total Venta</th>
            <th>Fecha Venta</th>
        </tr>
        <?php foreach ($ventas as $venta): ?>
            <tr>
                <td><?php echo htmlspecialchars($venta['nombre']); ?></td>
                <td><?php echo htmlspecialchars($venta['nombre_producto']); ?></td>
                <td><?php echo htmlspecialchars($venta['cantidad']); ?></td>
                <td><?php echo htmlspecialchars(number_format($venta['precio'], 2)); ?></td>
                <td><?php echo htmlspecialchars(number_format($venta['total_venta'], 2)); ?></td>
                <td><?php echo htmlspecialchars($venta['fecha']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php elseif (!empty($pedidos) && $tipoConsulta == 'pedidos'): ?>
  <h2>Pedidos del cliente seleccionado:</h2>
<table>
    <tr>
        <th>Cliente</th>
        <th>Producto</th>
        <th>Total kg/lts</th>
       
        <th>Fecha Pedido</th>
    </tr>
    <?php 
    // Verificar la estructura de los datos de pedidos antes de mostrarlos.
  
    
    foreach ($pedidos as $pedido): ?>
        <tr>
            <td><?php echo htmlspecialchars($pedido['cliente_nombre']); // Cambiar a cliente_nombre si existe ?></td>
            <td><?php echo htmlspecialchars($pedido['nombre_producto']); ?></td>
            <td><?php echo htmlspecialchars($pedido['total_kg_lts']); ?></td>
            <td><?php echo htmlspecialchars($pedido['fecha_pedido']); ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php elseif (isset($_POST['cliente_id'])): ?>
    <p>No se encontraron resultados para el cliente seleccionado.</p>
<?php endif; ?>

</body>
<script>


$(document).ready(function() {
        // Inicializar Select2 en el select con id "cliente"
        $('#cliente').select2({
            placeholder: '--Selecciona un cliente--',
            allowClear: true
        });
    });
</script>
</html>