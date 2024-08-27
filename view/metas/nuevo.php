<?php
$modelMeta = new ModelMeta();
$modelEmpleado = new ModelEmpleado();
$modelRuta = new ModelRuta();
$modelZona = new ModelZona();
$tiposEmpleados = $modelEmpleado->obtenerTiposEmpleados();
$zonas = $modelZona->obtenerZonasGas();
$tiposGananciaRuta = $modelRuta->listaTiposGananciaRutaTodos();
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div class="inline-block">
        <a href="#"><i class="fas fa-home fa-sm"></i></a> /
        <a href="../view/index.php?action=metas/index.php">Metas por zona</a> /
        <a href="../view/index.php?action=metas/nuevo.php">Nueva meta</a>
    </div>
</div>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Metas</h1>
</div>

<div class="row">
    <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="../controller/Metas/InsertarMeta.php" onsubmit="return validarMetas()" method="post" id="agregarMetaForm">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Zona</label>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control form-control-sm" name="zona" id="zona" required>
                                        <option value="" selected disable hidden>Seleccionar zona</option>
                                        <?php
                                        foreach ($zonas as $zona) {
                                            echo '<option value="' . $zona['idzona'] . '">' . $zona['nombre'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Nombre de la meta</label>
                                </div>
                                <div class="col-md-6"><input class="form-control form-control-sm" type="text" name="nombre" id="nombre" maxlength="49" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Descripcion</label>
                                </div>
                                <div class="col-md-6">
                                    <input class="form-control form-control-sm" type="text" name="descripcion" id="descripcion" maxlength="99">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Tipos de empleados</label>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control form-control-sm" name="tipoEmpleado" id="tipoEmpleado" required>
                                        <option value="" selected disable hidden>Selecciones opción</option>
                                        <?php
                                        foreach ($tiposEmpleados as $tiposEmpleado) :
                                        ?>
                                            <option value="<?= $tiposEmpleado['idtipoempleado'] ?>"><?= $tiposEmpleado['nombre'] ?></option>
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
                                            <option value="<?= $tipoGananciaRuta['idtipogananciaruta'] ?>"><?= $tipoGananciaRuta['nombre'] ?></option>
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
                                        <option value="0" selected>No aplica</option>
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
                                        <input class="form-check-input" type="checkbox" name="total_pipas" id="total_pipas" value="1">
                                        <label class="form-check-label" for="total_pipas">Total litros pipas contado</label><br>
                                        <input class="form-check-input" type="checkbox" name="pipas_descuento" id="pipas_descuento" value="2">
                                        <label class="form-check-label" for="pipas_descuento">Total litros pipas con descuento</label><br>
                                        <input class="form-check-input" type="checkbox" name="total_estaciones" id="total_estaciones" value="3">
                                        <label class="form-check-label" for="total_estaciones">Total litro estaciones</label><br>
                                        <input class="form-check-input" type="checkbox" name="total_cilindros" id="total_cilindros" value="4">
                                        <label class="form-check-label" for="total_cilindros">Total cilindros</label><br>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row" id="trdescuento">
                                <div class="col-md-2">
                                    <label>¿Descuento?:</label>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="descuento" id="descuento" value="1">
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
                                    <input type="number" name="tipoVenta" id="tipoVenta" value="2" hidden>
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
                                    <input class="form-control form-control-sm" type="number" placeholder="Meta #1" name="meta1" id="meta1" min="0" step="0.01" required>
                                </div>
                                <div class="col-md-3">
                                    <input class="form-control form-control-sm" name="comision1" placeholder="Comisión a la meta #1" type="number" step="0.01" min="0" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Meta 2</label>
                                </div>
                                <div class="col-md-3">
                                    <input class="form-control form-control-sm" type="number" placeholder="Meta #2" name="meta2" id="meta2" step="0.01" min="0" required>
                                </div>
                                <div class="col-md-3">
                                    <input class="form-control form-control-sm" name="comision2" placeholder="Comisión a la meta #2" type="number" min="0" step="0.01" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Meta 3</label>
                                </div>
                                <div class="col-md-3">
                                    <input class="form-control form-control-sm" type="number" placeholder="Meta #3" name="meta3" id="meta3" min="0" step="0.01" required>
                                </div>
                                <div class="col-md-3">
                                    <input class="form-control form-control-sm" name="comision3" placeholder="Comisión a la meta #3" type="number" min="0" step="0.01" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Meta 4</label>
                                </div>
                                <div class="col-md-3">
                                    <input class="form-control form-control-sm" type="number" placeholder="Meta #4" name="meta4" id="meta4" min="0" step="0.01" required>
                                </div>
                                <div class="col-md-3">
                                    <input class="form-control form-control-sm" name="comision4" placeholder="Comisión a la meta #4" type="number" min="0" step="0.01" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Meta 5 (Más alta)</label>
                                </div>
                                <div class="col-md-3">
                                    <input class="form-control form-control-sm" type="number" placeholder="Meta #5" name="meta5" id="meta5" min="0" step="0.01" required>
                                </div>
                                <div class="col-md-3">
                                    <input class="form-control form-control-sm" name="comision5" placeholder="Comisión a la meta #5" type="number" min="0" step="0.01" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 offset-md-11">
                                    <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    cargarSeccionesTipoEmpleado();

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
        cargarSeccionesTipoEmpleado();
    });

    $("#tipoEmpleado").change(function() {
        cargarSeccionesTipoEmpleado();
    });

    function cargarSeccionesTipoEmpleado() {
        let tipoEmpleadoId = $("#tipoEmpleado").val();
        let tipoMeta = $("#tipoMeta").val();

        if (tipoEmpleadoId == 5) { //Vendedores cilindros
            $("#tipoMeta").val("Meta: Cilindros")
            $("#tipoVenta").val("1");
        } else {
            $("#tipoMeta").val("Meta:")
            $("#tipoVenta").val("2")
        }
        if (tipoEmpleadoId == 1 || tipoEmpleadoId == 3) { //Vendedores y ayudades de pipas manejan descuentos
            $("#trdescuento").show();
        } else {
            $("#trdescuento").hide();
        }

        if (tipoEmpleadoId == 2) { //Gerentes
            $("#tipoventatr").show();
        } else {
            $("#tipoventatr").hide();
            // Desmarcar los checkboxes dentro de #tipoventatr
            $("#tipoventatr input[type='checkbox']").prop('checked', false);
        }

        if (tipoEmpleadoId == 6) { //Vendedor estación
            //Si es un empleado "Vendedor de estación", se cargan las rutas-estaciones para verificar las metas por estación
            let zonaId = $("#zona").val();
            $("#ruta").empty().append('<option value="" selected disabled>Ruta</option>');
            $.ajax({
                data: {
                    zonaId: zonaId,
                    tipoRutaId: 5
                },
                type: "GET",
                url: '../controller/Rutas/ObtenerRutasZonaTipo.php',
                dataType: "json",
                success: function(data) {
                    $.each(data, function(key, ruta) {
                        $("#ruta").append('<option value=' + ruta.idruta + '>' + ruta.clave_ruta + '</option>');
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

    function validarMetas() {
        var meta1 = parseFloat(document.getElementById('meta1').value);
        var meta2 = parseFloat(document.getElementById('meta2').value);
        var meta3 = parseFloat(document.getElementById('meta3').value);
        var meta4 = parseFloat(document.getElementById('meta4').value);
        var meta5 = parseFloat(document.getElementById('meta5').value);

        if (meta2 <= meta1) {
            alert('Meta #2 debe ser mayor que Meta #1');
            return false;
        }
        if (meta3 <= meta2) {
            alert('Meta #3 debe ser mayor que Meta #2');
            return false;
        }
        if (meta4 <= meta3) {
            alert('Meta #4 debe ser mayor que Meta #3');
            return false;
        }
        if (meta5 <= meta4) {
            alert('Meta #5 debe ser mayor que Meta #4');
            return false;
        }
        var alMenosUnCheckboxSeleccionado = $("#tipoventatr input[type='checkbox']:checked").length > 0;
        if ($("#tipoEmpleado").val() == 2) { //Gerente
            // Verificar si al menos un checkbox está seleccionado
            if (!alMenosUnCheckboxSeleccionado) {
                alert('Debes seleccionar al menos un valor a tomar en cuenta para esta meta');
                return false;
            }
        }

        // Devolver true si todas las validaciones son exitosas
        return true;
    }
</script>