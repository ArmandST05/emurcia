<?php
$modelZona = new ModelZona();
$modelCompania = new ModelCompania();
$modelAutoconsumo = new ModelAutoconsumo();
// Búsqueda de datos
$fechaInicial = (!empty($_GET['fechaInicial'])) ? $_GET['fechaInicial'] : date("Y-m-d");
$fechaFinal = (!empty($_GET['fechaFinal'])) ? $_GET['fechaFinal'] : date("Y-m-d");
$rutaId = (!empty($_GET['ruta'])) ? $_GET['ruta'] : 0;
$productoNombre = (!empty($_GET['producto'])) ? $_GET['producto'] : "0";

if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "inv") {
    $zonas = $modelZona->obtenerZonasGas();
    $companias = $modelCompania->listaPorEstatus(1);
    $zonaId = (!empty($_GET['zona'])) ? $_GET['zona'] : 0;
    $companiaId = (!empty($_GET['compania'])) ? $_GET['compania'] : 0;
} else {
    $companiaId = 0;
    $zonaId = $_SESSION['zonaId'];
}

if ($companiaId != 0) {
    // Búsqueda por compañía - Obtener autoconsumos de todas las zonas de la compañía
    if ($productoNombre != "0") {
        $autoconsumos = $modelAutoconsumo->obtenerAutoconsumosCompaniaProductoFecha($companiaId, $productoNombre, $fechaInicial, $fechaFinal);
    } else {
        $autoconsumos = $modelAutoconsumo->obtenerAutoconsumosCompaniaFecha($companiaId, $fechaInicial, $fechaFinal);
    }
} else if ($rutaId != 0) {
    // Búsqueda por ruta
    if ($productoNombre != "0") {
        $autoconsumos = $modelAutoconsumo->obtenerAutoconsumosRutaProductoFecha($rutaId, $productoNombre, $fechaInicial, $fechaFinal);
    } else {
        $autoconsumos = $modelAutoconsumo->obtenerAutoconsumosRutaFecha($rutaId, $fechaInicial, $fechaFinal);
    }
} else {
    // Búsqueda por zona (No se especificó ruta) - Obtener autoconsumos de todas las rutas de la zona
    if ($productoNombre != "0") {
        $autoconsumos = $modelAutoconsumo->obtenerAutoconsumosZonaProductoFecha($zonaId, $productoNombre, $fechaInicial, $fechaFinal);
    } else {
        $autoconsumos = $modelAutoconsumo->obtenerAutoconsumosZonaFecha($zonaId, $fechaInicial, $fechaFinal);
    }
}

// Ordenar los datos
$datosAutoconsumo = [];
foreach ($autoconsumos as $autoconsumo) {
    $datosAutoconsumo[$autoconsumo['compania_id']][$autoconsumo['zona_id']][] = $autoconsumo;
}
?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div class="inline-block">
        <a href="#"><i class="fas fa-home fa-sm"></i></a> /
        <a href="#">Autoconsumos</a>
    </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Autoconsumos</h1>
    <div>
        <?php if ($_SESSION["tipoUsuario"] == "u") : ?>
            <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" href="index.php?action=autoconsumos/nuevo.php">Nuevo</a>
        <?php endif; ?>
        <?php
        // Mostrar solo si la zona está permitida
        $zonasPermitidas = [1, 3, 5, 8];
        if (in_array($_SESSION["zonaId"], $zonasPermitidas)) :
        ?>
            <a class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm ml-2" href="index.php?action=autoconsumos/index_estaciones.php">Autoconsumos estaciones</a>
        <?php endif; ?>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Buscar</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body" name="buscar" id="buscar">
                <form action='index.php' method='GET'>
                    <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "inv") : ?>
                        <div class="row">
                            <div class="col-lg-1 col-sm-6">
                                <div class="form-group">
                                    <label>Compañía:</label>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group">
                                    <select class="form-control form-control-sm" name="compania" id="compania">
                                        <option value="0" selected>Selecciona opción</option>
                                        <?php foreach ($companias as $compania) : ?>
                                            <option value="<?php echo $compania['idcompania'] ?>" <?php echo ($companiaId == $compania['idcompania']) ? "selected" : "" ?>>
                                                <?php echo $compania["nombre"] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-1 col-sm-6">
                                <div class="form-group">
                                    <label>Zona:</label>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group">
                                    <select class="form-control form-control-sm" name="zona" id="zona">
                                        <option selected value="0">Selecciona opción</option>
                                        <?php foreach ($zonas as $dataZona) : ?>
                                            <option value="<?php echo $dataZona['idzona'] ?>" <?php echo ($zonaId == $dataZona['idzona']) ? "selected" : "" ?>>
                                                <?php echo strtoupper($dataZona["nombre"]) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-lg-1 col-sm-6">
                            <div class="form-group">
                                <label>Ruta:</label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <select class="form-control form-control-sm" name="ruta" id="ruta">
                                    <option value="0">Todas</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-1 col-sm-6">
                            <div class="form-group">
                                <label>Desde:</label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <input class="form-control form-control-sm" type="date" name="fechaInicial" value="<?php echo $fechaInicial ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-1 col-sm-6">
                            <div class="form-group">
                                <label>Hasta:</label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <input class="form-control form-control-sm" type="date" name="fechaFinal" value="<?php echo $fechaFinal ?>" required>
                        </div>
                    </div>
                    <input type='hidden' name='action' id='action' value="autoconsumos/index.php" />
                    <div class="row">
                        <div clas="col-md-1 offset-md-10">
                            <input class="btn btn-primary btn-sm" type='submit' id='busqueda' value='Buscar'>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <!-- Card -->
    <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
            <!-- Card Header -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Lista de Autoconsumos</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Compañía</th>
                                <th>Zona</th>
                                <th>Ruta</th>
                                <th>Producto</th>
                                <th>Litros</th>
                                <th>Costo</th>
                                <th>Costo Total</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($datosAutoconsumo)) : ?>
                                <?php foreach ($datosAutoconsumo as $companiaId => $zonas) : ?>
                                    <?php foreach ($zonas as $zonaId => $autoconsumos) : ?>
                                        <?php foreach ($autoconsumos as $autoconsumo) : ?>
                                            <tr>
                                                <td><?php echo $autoconsumo['compania_nombre'] ?></td>
                                                <td><?php echo $autoconsumo['zona_nombre'] ?></td>
                                                <td><?php echo $autoconsumo['ruta_nombre'] ?></td>
                                                <td><?php echo $autoconsumo['producto_nombre'] ?></td>
                                                <td><?php echo $autoconsumo['litros'] ?></td>
                                                <td><?php echo $autoconsumo['costo'] ?></td>
                                                <td><?php echo $autoconsumo['costo_total'] ?></td>
                                                <td><?php echo $autoconsumo['fecha'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

