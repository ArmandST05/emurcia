<?php
// Crear la instancia de ModelVenta
$modelVenta = new ModelVenta();

// Obtener la lista de clientes
$clientesDescuento = $modelVenta->obtenerClientes();
$modelProducto = new ModelProducto();
$productos = $modelProducto->index();
$fechaMinima = date(("Y-m-d"), strtotime("-7 days"));
$fechaInicial = ((isset($_GET["fechaInicial"]) && ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc")) || (($_SESSION["tipoUsuario"] == "u" || $_SESSION['tipoUsuario'] == "mv") && isset($_GET["fechaInicial"]) >= $fechaMinima)) ? $_GET["fechaInicial"] : date("Y-m-d");
$fechaFinal = ((isset($_GET["fechaFinal"]) && ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc"))  || (($_SESSION["tipoUsuario"] == "u" || $_SESSION['tipoUsuario'] == "mv") && isset($_GET["fechaFinal"]) >= $fechaMinima)) ? $_GET["fechaFinal"] : date("Y-m-d");
$fechaActual = date("Y-m-d");
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
            font-size: 0.9em;
        }
        th {
            background-color: #f2f2f2;
        }
        .btn {
            padding: 5px 10px;
            font-size: 0.9em;
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            border-radius: 3px;
        }
        .btn:hover {
            background-color: #0056b3;
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
            <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "mv") : ?>
              <!-- Otros campos aquí si es necesario -->
            <?php endif; ?>
            <div class="col-md-4">
              <div class="form-group">
                <label>Cliente:</label>
                <select  class="form-control form-control-sm" name="cliente_id" id="cliente" required>
                    <option value="">--Selecciona un cliente--</option>
                    <?php foreach ($clientesDescuento as $cliente): ?>
                        <option value="<?php echo $cliente['idclientedescuento']; ?>" 
                            <?php echo isset($clienteId) && $clienteId == $cliente['idclientedescuento'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cliente['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Desde:</label>
                <input class="form-control form-control-sm" type="date" id="fechaInicial" name="fechaInicial" value="<?php echo $fechaInicial ?>" <?php echo (($_SESSION["tipoUsuario"] == "u" || $_SESSION['tipoUsuario'] == "mv") ? "min='" . $fechaMinima . "'" : "max='" . $fechaActual . "'") ?>>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Hasta:</label>
                <input class="form-control form-control-sm" type="date" id="fechaFinal" name="fechaFinal" value="<?php echo $fechaFinal ?>" <?php echo (($_SESSION["tipoUsuario"] == "u" || $_SESSION['tipoUsuario'] == "mv") ? "min='" . $fechaMinima . "'" : "max='" . $fechaActual . "'") ?>>
              </div>
            </div>
          </div>



          <div class="row">
            <div class="col-md-2 pull-right">
            <button class="btn btn-primary btn-sm" type="submit">Mostrar Ventas</button>
            </div>
          </div>
    </form>
</div>
</div>
</div>
</div>


<?php if (!empty($ventas)): ?>
    <h2>Ventas del cliente seleccionado:</h2>
    <table>
        <tr>
            <th>Cliente</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>credito</th>
            <th>contado</th>
            <th>descuento</th>
            <th>Precio</th>
            <th>Total Venta</th>
            <th>Fecha Venta</th>
        </tr>
        <?php foreach ($ventas as $venta): ?>
            <tr>
                <td><?php echo htmlspecialchars($venta['nombre']); ?></td>
                <td><?php echo htmlspecialchars($venta['nombre_producto']); ?></td>
                <td><?php echo htmlspecialchars($venta['cantidad']); ?></td>
                <td><?php echo htmlspecialchars($venta['total_venta_credito']); ?></td>
                <td><?php echo htmlspecialchars($venta['descuento_total_venta_contado']); ?></td>
                <td><?php echo htmlspecialchars($venta['cantidad_venta_contado']); ?></td>
                <td><?php echo htmlspecialchars(number_format($venta['precio'], 2)); ?></td>
                <td><?php echo htmlspecialchars(number_format($venta['total_venta'], 2)); ?></td>
                <td><?php echo htmlspecialchars($venta['fecha']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php elseif (isset($_POST['cliente_id'])): ?>
    <p>No se encontraron ventas para el cliente seleccionado.</p>
<?php endif; ?>

</body>
</html>
