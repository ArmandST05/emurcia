<?php

// Incluye el archivo del modelo

// Crea una instancia del modelo
$modelNomina = new ModelNomina();

// Maneja la eliminación del fondo si se envía una solicitud POST
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'eliminar') {
    $fondoId = $_POST['id'];

    // Llama a la función para eliminar el fondo
    $resultado = $modelNomina->eliminarFondo($fondoId);

    // Establece el mensaje de éxito o error
    echo json_encode(['resultado' => $resultado]);
    exit(); // Termina la ejecución aquí para que no se ejecute el resto del código
}

// Obtén los fondos de la base de datos
$fondos = $modelNomina->obtenerFondos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Fondos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
<div class="container mt-4">
    <h1 class="mb-4">Gestión de Fondos</h1>

    <!-- Mensaje de éxito o error -->
    <div id="mensaje" class="alert alert-info" style="display: none;"></div>

    <table class="table table-bordered" id="tablaFondos">
        <thead>
            <tr>
                <th>ID</th>
                <th>Valor</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($fondos as $fondo) : ?>
                <tr id="fondo-<?php echo htmlspecialchars($fondo['id']); ?>">
                    <td><?php echo htmlspecialchars($fondo['id']); ?></td>
                    <td><?php echo htmlspecialchars($fondo['valor_fondo']); ?></td>
                    <td>
                        <button onclick="confirmarEliminacion(<?php echo htmlspecialchars($fondo['id']); ?>)" class="btn btn-danger btn-sm">Eliminar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function confirmarEliminacion(fondoId) {
    if (confirm("¿Estás seguro de que deseas eliminar este fondo?")) {
        $.ajax({
            url: '', // La URL es la misma página
            type: 'POST',
            data: {
                id: fondoId,
                action: 'eliminar'
            },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.resultado) {
                    $('#fondo-' + fondoId).remove(); // Elimina la fila de la tabla
                    $('#mensaje').text('Fondo eliminado correctamente').show(); // Muestra el mensaje
                } else {
                    $('#mensaje').text('Error al eliminar el fondo').show(); // Muestra el mensaje
                }
            },
            error: function() {
                $('#mensaje').text('Error en la solicitud').show(); // Muestra el mensaje de error
            }
        });
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
