<?php
$modelZona = new ModelZona();
$modelCompania = new ModelCompania();
$modelAutoconsumo = new ModelAutoconsumo();

// Parámetros de búsqueda
$fechaInicial = (!empty($_GET['fechaInicial'])) ? $_GET['fechaInicial'] : date("Y-m-d");
$fechaFinal = (!empty($_GET['fechaFinal'])) ? $_GET['fechaFinal'] : date("Y-m-d");
$rutaId = (!empty($_GET['ruta'])) ? $_GET['ruta'] : 0;

if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "inv") {
    $zonas = $modelZona->obtenerZonasGas();
    $companias = $modelCompania->listaPorEstatus(1);
    $zonaId = (!empty($_GET['zona'])) ? $_GET['zona'] : 0;
    $companiaId = (!empty($_GET['compania'])) ? $_GET['compania'] : 0;
} else {
    $companiaId = 0;
    $zonaId = $_SESSION['zonaId'];
}

$autoconsumos = [];
$autoconsumos = $modelAutoconsumo->ObtenerAutoconsumosEstaciones($companiaId, $fechaInicial, $fechaFinal);

?>

<!-- Filtros -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Buscar</h6>
    </div>
    <div class="card-body" name="buscar" id="buscar">
        <form action='index.php' method='GET'>
            <input type="hidden" name="action" value="autoconsumos/index_estaciones.php">

            <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "inv") : ?>
                <div class="row">
                    <div class="col-lg-2">
                        <label>Compañía:</label>
                        <select class="form-control form-control-sm" name="compania">
                            <option value="0">Selecciona opción</option>
                            <?php foreach ($companias as $compania) : ?>
                                <option value="<?= $compania['idcompania'] ?>" <?= ($companiaId == $compania['idcompania']) ? "selected" : "" ?>>
                                    <?= $compania["nombre"] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-lg-2">
                        <label>Zona:</label>
                        <select class="form-control form-control-sm" name="zona">
                            <option value="0">Selecciona opción</option>
                            <?php foreach ($zonas as $dataZona) : ?>
                                <option value="<?= $dataZona['idzona'] ?>" <?= ($zonaId == $dataZona['idzona']) ? "selected" : "" ?>>
                                    <?= strtoupper($dataZona["nombre"]) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row mt-3">
                <div class="col-lg-2">
                    <label>Estación:</label>
                    <select class="form-control form-control-sm" name="ruta" id="ruta">
                        <option value="0">Todas</option>
                        <?php 
                            $estaciones = $modelAutoconsumo->obtenerEstaciones($zonaId);
                        
                        ?>
                        <?php foreach ($estaciones as $ruta) : ?>
    <option value="<?= $ruta['idruta'] ?>" <?= ($rutaId == $ruta['idruta']) ? 'selected' : '' ?>>
        <?= $ruta['clave_ruta'] ?>
    </option>
<?php endforeach; ?>

                    </select>
                </div>
                <div class="col-lg-2">
                    <label>Desde:</label>
                    <input type="date" class="form-control form-control-sm" name="fechaInicial" value="<?= $fechaInicial ?>" required>
                </div>

                <div class="col-lg-2">
                    <label>Hasta:</label>
                    <input type="date" class="form-control form-control-sm" name="fechaFinal" value="<?= $fechaFinal ?>" required>
                </div>

                <div class="col-lg-2 align-self-end">
                    <button class="btn btn-primary btn-sm" type="submit">Buscar</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Resultados -->
<?php if (!empty($autoconsumos)) : ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Resultados</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-hover text-center">
                    <thead class="thead-light">
                        <tr>
                            <th>Zona</th>
                            <th>Estación</th>
                            <th>Litros</th>
                            <th>Costo</th>
                            <th>Total</th>
                            <th>Fecha</th> <!-- Nueva columna para la fecha -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($autoconsumos as $row) : ?>
                            <?php if ($rutaId == 0 || $rutaId == $row['ruta_id']) : ?>
                                <tr>
                                    <td><?= $row['zona_nombre'] ?></td>
                                    <td><?= $row['ruta_nombre'] ?></td>
                                    <td><?= number_format($row['litros'], 2) ?></td>
                                    <td>$<?= number_format($row['costo'], 2) ?></td>
                                    <td>$<?= number_format($row['total'], 2) ?></td>
                                    <td><?= isset($row['fecha']) ? date('Y-m-d', strtotime($row['fecha'])) : 'N/A' ?></td> <!-- Mostrar la fecha en formato YYYY-MM-DD -->
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

