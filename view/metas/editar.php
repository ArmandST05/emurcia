<?php
$modelMeta = new ModelMeta();
$modelEmpleado = new ModelEmpleado();
$modelZona = new ModelZona();
$modelRuta = new ModelRuta();

$id = $_GET["id"];
$meta = $modelMeta->obtenerMetaPorId($id);
$zonaId = $meta["zona_id"];
$tiposEmpleados = $modelEmpleado->obtenerTiposEmpleados();
$rutas = $modelRuta->obtenerZona($meta['zona_id']);
$tiposGananciaRuta = $modelRuta->listaTiposGananciaRutaTodos();
$zonas = $modelZona->obtenerZonasGas();
$ambitoComisionesGerente = [];

if (!$id) {
    header('Location: ../../view/index.php?action=metas/index.php');
    exit();
}

function validarInputCheck($array, $valor)
{
    foreach ($array as $item) {
        if ($item->ambito_comision_id == $valor) echo ("checked");
        break;
    }
}
?>
<!-- aquí vamos a editar una meta -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div class="inline-block">
        <a href="#"><i class="fas fa-home fa-sm"></i></a> /
        <a href="../view/index.php?action=metas/index.php">Metas y comisiones</a> /
        <a href="#">Editar meta</a>
    </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Editar meta</h1>
</div>

<!-- Sección para editar la meta seleccionada -->

<div class="row">
    <!-- Editar Empleado -->
    <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
            <!-- Card Body -->
            <div class="card-body">
                <form action="../controller/Metas/ActualizarMeta.php" method="post" id="editarMetaform">
                    <div class="row">
                        <div class="col-md-12">
                            <!--<div class="row">
                                <div class="col-md-2">
                                    <label>Zona</label>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control form-control-sm" name="zona" id="zona" required>
                                        <option value="" selected disable hidden>Zona</option>
                                        <?php
                                        foreach ($zonas as $zona) {
                                            $selected = ($zona['idzona'] == $meta['zona_id']) ? 'selected' : '';
                                            echo '<option value="' . $zona['idzona'] . '" ' . $selected . ' >' . $zona['nombre'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>-->
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Nombre de la meta</label>
                                </div>
                                <div class="col-md-6">
                                    <td><input class="form-control form-control-sm" type="text" name="nombre" id="nombre" value="<?php echo $meta["nombre"]; ?>" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Descripción</label>
                                </div>
                                <div class="col-md-6">
                                    <input class="form-control form-control-sm" type="text" name="descripcion" id="descripcion" value="<?php echo $meta["descripcion"]; ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Tipo de empleado</label>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control form-control-sm" name="tipoEmpleado" id="tipoEmpleado" required>
                                        <option value="" selected disable hidden>Selecciones opción</option>
                                        <?php
                                        foreach ($tiposEmpleados as $tiposEmpleado) :
                                            $selected = $tiposEmpleado['idtipoempleado'] == $meta['tipo_empleado_id'] ? "selected" : "";
                                        ?>
                                            <option value="<?= $tiposEmpleado['idtipoempleado'] ?>" <?= $selected ?>><?= $tiposEmpleado['nombre'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Tipo de venta</label>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control form-control-sm" name="tipoGananciaRuta" id="tipoGananciaRuta" required>
                                        <?php
                                        foreach ($tiposGananciaRuta as $tipoGananciaRuta) :
                                        ?>
                                            <option value="<?php echo $tipoGananciaRuta['idtipogananciaruta'] ?>" <?php echo ($meta["tipo_ganancia_ruta_id"] == $tipoGananciaRuta['idtipogananciaruta']) ? "selected" : "" ?>><?= $tipoGananciaRuta['nombre'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Ruta</label>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control form-control-sm" name="ruta" id="ruta" required>

                                        <option value="" disabled hidden>Selecciona una opción</option>
                                        <?php
                                        if ($meta['ruta_id']) :
                                            foreach ($rutas as $ruta) :
                                                $selectedRuta = $meta["ruta_id"] == $ruta['idruta'] ? "selected" : "";
                                        ?>
                                                <option value="<?= $ruta['idruta'] ?>" <?= $selectedRuta ?>><?= $ruta['clave_ruta'] ?></option>
                                            <?php endforeach;
                                        else : ?>
                                            <option value="">No aplica</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row" id="tipoventatr">
                                <div class="col-md-2">
                                    <label>Seleccione que valores tomar en cuenta</label>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <?php
                                        if ($meta['tipo_empleado_id'] == 2) {
                                            $ambitoComisionesGerente = $modelMeta->obtenerMetaGerente($id);
                                        }
                                        ?>
                                        <input class="form-check-input" type="checkbox" name="total_pipas" id="total_pipas" <?= validarInputCheck($ambitoComisionesGerente, 1) ?> value="1">
                                        <label class="form-check-label" for="total_pipas">Total litros pipas contado</label><br>
                                        <input class="form-check-input" type="checkbox" name="pipas_descuento" id="pipas_descuento" <?= validarInputCheck($ambitoComisionesGerente, 2) ?> value="2">
                                        <label class="form-check-label" for="pipas_descuento">Total litros pipas con descuento</label><br>
                                        <input class="form-check-input" type="checkbox" name="total_estaciones" id="total_estaciones" <?= validarInputCheck($ambitoComisionesGerente, 3) ?> value="3">
                                        <label class="form-check-label" for="total_estaciones">Total litro estaciones</label><br>
                                        <input class="form-check-input" type="checkbox" name="total_cilindros" id="total_cilindros" <?= validarInputCheck($ambitoComisionesGerente, 4) ?> value="4">
                                        <label class="form-check-label" for="total_cilindros">Total cilindros</label><br>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <?php $trdescuentohide = $meta['tipo_empleado_id'] == 2 ? "hidden" : "";
                            ?>
                            <div class="row" id="trdescuento" <?= $trdescuentohide ?>>
                                <div class="col-md-2">

                                    <label>¿Descuento?</label>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="descuento" id="descuento" value="1" <?php echo ($meta['para_descuento']) ? "checked" : "" ?>>
                                        <label class="form-check-label" for="descuento">
                                            Con descuento
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-2">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control form-control-sm" name="tipoMeta" id="tipoMeta" value="Meta: Litros" disabled>
                                </div>
                                <div class="col-md-3">
                                    <label>Comisión:</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Meta 1 (más baja)</label>
                                </div>
                                <div class="col-md-3">
                                    <input value="<?= $meta['meta1'] ?>" class="form-control form-control-sm" type="number" placeholder="Meta mínima" name="meta1" id="meta1" min="0" step="0.01" required>
                                </div>
                                <div class="col-md-3">
                                    <input value="<?= $meta['comision1'] ?>" class="form-control form-control-sm" name="comision1" placeholder="Comisión a la meta #1" type="number" min="0" step="0.01" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Meta 2</label>
                                </div>
                                <div class="col-md-3">
                                    <input value="<?= $meta['meta2'] ?>" class="form-control form-control-sm" type="number" placeholder="Meta #2" name="meta2" id="meta2" min="0" step="0.01" required>
                                </div>
                                <div class="col-md-3">
                                    <input value="<?= $meta['comision2'] ?>" class="form-control form-control-sm" name="comision2" placeholder="Comisión a la meta #2" type="number" min="0" step="0.01" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Meta 3</label>
                                </div>
                                <div class="col-md-3">
                                    <input value="<?= $meta['meta3'] ?>" class="form-control form-control-sm" type="number" placeholder="Meta #3" name="meta3" id="meta3" min="0" step="0.01" required>
                                </div>
                                <div class="col-md-3">
                                    <input value="<?= $meta['comision3'] ?>" class="form-control form-control-sm" name="comision3" placeholder="Comisión a la meta #3" type="number" min="0" step="0.01" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <label>Meta 4</label>
                                </div>
                                <div class="col-md-3">
                                    <input value="<?= $meta['meta4'] ?>" class="form-control form-control-sm" type="number" placeholder="Meta #4" name="meta4" id="meta4" min="0" step="0.01" required>
                                </div>
                                <div class="col-md-3">
                                    <input value="<?= $meta['comision4'] ?>" class="form-control form-control-sm" name="comision4" placeholder="Comisión a la meta #4" type="number" min="0" step="0.01" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <label>Meta 5</label>
                                </div>
                                <div class="col-md-3">
                                    <input value="<?= $meta['meta5'] ?>" class="form-control form-control-sm" type="number" placeholder="Meta #5" name="meta5" id="meta5" min="0" step="0.01" required>
                                </div>
                                <div class="col-md-3">
                                    <input value="<?= $meta['comision5'] ?>" class="form-control form-control-sm" name="comision5" placeholder="Comisión a la meta #5" type="number" min="0" step="0.01" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1 offset-md-11">
                            <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<script>
    recargarDatos();

    function validarCheckbox() {
        // Verifica si el checkbox con value 4 está seleccionado
        if ($("#tipoventatr input[value='4']").prop('checked')) {
            // Desmarca todos los otros checkboxes si el checkbox con value 4 está seleccionado
            $("#tipoventatr input[type='checkbox']").not("[value='4']").prop('checked', false);
        } else {
            // Desmarca el checkbox con value 4 si se selecciona cualquier otro checkbox
            $("#tipoventatr input[value='4']").prop('checked', false);
        }
    }

    // Asigna la función al evento clic de los checkboxes dentro de #tipoventatr
    $("#tipoventatr input[type='checkbox']").on('click', validarCheckbox);
    $('#zona').select2({});
    $("#zona").change(function() {
        recargarDatos();
    });
    $("#tipoEmpleado").change(function() {
        recargarDatos();
    });


    function recargarDatos() {
        let tipoEmpleadoId = $("#tipoEmpleado").val();
        let tipoMeta = $("#tipoMeta").val();

        if (tipoEmpleadoId == 5) {
            $("#tipoMeta").val("Meta: Cilindros")
            $("#tipo_venta").val("1");
        } else {
            $("#tipoMeta").val("Meta:")
            $("#tipo_venta").val("2")
        }
        if (tipoEmpleadoId == 1 || tipoEmpleadoId == 3) {
            $("#trdescuento").show();

        } else $("#trdescuento").hide();
        if (tipoEmpleadoId == 2) {
            $("#tipoventatr").show();
        } else {
            $("#tipoventatr").hide();
            // Desmarcar los checkboxes dentro de #tipoventatr
            $("#tipoventatr input[type='checkbox']").prop('checked', false);
        }

        if (tipoEmpleadoId == 6) { //Vendedor estación
            //Si es un empleado "Vendedor de estación", se cargan las rutas-estaciones para verificar las metas por estación

            $("#ruta").empty().append('<option value="" selected disabled>Ruta</option>');
            $.ajax({
                data: {
                    zonaId: <?php echo $zonaId ?>,
                    tipoRutaId: 5
                },
                type: "GET",
                url: '../controller/Rutas/ObtenerRutasZonaTipo.php',
                dataType: "json",
                success: function(data) {
                    $.each(data, function(key, ruta) {
                        let selected = "";
                        if ("<?php echo $meta["ruta_id"] ?>" == ruta.idruta) {
                            selected = "selected";
                        }
                        $("#ruta").append('<option value="' + ruta.idruta + '" ' + selected + '>' + ruta.clave_ruta + '</option>');
                    });
                },
                error: function(data) {
                    alertify.error('Ha ocurrido un error al cargar las rutas');
                }
            });
        } else {
            $("#ruta").empty().append('<option value="0" selected >No aplica</option>');
        }
    }
</script>