<?php
// Crear la instancia de ModelVenta
$modelVenta = new ModelVenta();

// Obtener la lista de clientes
$clientesDescuento = $modelVenta->obtenerClientes();

// Manejar la selección de un cliente
$ventas = [];
if (isset($_POST['cliente_id']) && is_numeric($_POST['cliente_id'])) {
    $clienteId = $_POST['cliente_id'];
    $ventas = $modelVenta->obtenerVentasPorCliente($clienteId);
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
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<form method="POST" action="">
    <label for="cliente">Selecciona un cliente:</label>
    <select name="cliente_id" id="cliente" required>
        <option value="">--Selecciona un cliente--</option>
        <?php foreach ($clientesDescuento as $cliente): ?>
            <option value="<?php echo $cliente['idclientedescuento']; ?>" 
                <?php echo isset($clienteId) && $clienteId == $cliente['idclientedescuento'] ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($cliente['nombre']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Mostrar Ventas</button>
</form>

<?php if (!empty($ventas)): ?>
    <h2>Ventas del cliente seleccionado:</h2>
    <table>
        <tr>
            <th>ID Venta</th>
            <th>Detalle Venta ID</th>
            <th>Cantidad</th>
            <th>Total</th>
            <th>Fecha</th>
        </tr>
        <?php foreach ($ventas as $venta): ?>
            <tr>
                <td><?php echo htmlspecialchars($venta['idventaclientedescuento']); ?></td>
                <td><?php echo htmlspecialchars($venta['detalle_venta_id']); ?></td>
                <td><?php echo htmlspecialchars($venta['cantidad']); ?></td>
                <td><?php echo htmlspecialchars($venta['total']); ?></td>
                <td><?php echo htmlspecialchars($venta['created_at']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php elseif (isset($_POST['cliente_id'])): ?>
    <p>No se encontraron ventas para el cliente seleccionado.</p>
<?php endif; ?>

</body>
</html>
