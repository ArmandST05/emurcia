<?php
$modelGasto = new ModelGasto();
$modelConceptoGasto = new ModelConceptoGasto();
$modelCategoriaGasto = new ModelCategoriaGasto();
$tiposGastoData = $modelGasto->listaTiposGasto();
$tipoGastoId = (isset($_GET["tipoGasto"])) ? $_GET["tipoGasto"] : 1;
$tipoGastoNombre = $tiposGastoData[array_search($tipoGastoId, array_column($tiposGastoData, "idtipogasto"))]["nombre"];

$categorias = $modelCategoriaGasto->listaPorTipoGasto($tipoGastoId);
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Gastos</a> /
    <a href="index.php?action=gastosconfiguracion/index.php">Configuración</a> /
    <a href="#">Categorías y Conceptos</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Administrar Categorías y Conceptos</h1>
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
      <div class="card-body" name="otra" id="otra">
        <form action='index.php' method='GET'>
          <input type='hidden' name='action' id='action' value="gastosconfiguracion/categorias_conceptos.php" />
          <div class="row">
            <div class="col-md-2">
              <div class="form-group">
                <label>Tipo de gasto:</label>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <select class="form-control form-control-sm" name="tipoGasto">
                  <?php foreach ($tiposGastoData as $tipo) : ?>
                    <option value="<?php echo $tipo['idtipogasto'] ?>" <?php echo ($tipoGastoId == $tipo['idtipogasto']) ? "selected" : "" ?>>
                      <?php echo strtoupper($tipo["nombre"]) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-2">
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
  <!-- Nuevo -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Header -->
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">LISTA <?php echo $tipoGastoNombre ?></h6>
      </div>
      <!-- Card Body -->
      <div class="card-body">
        <div class="row">
          <div class="col-md-4 offset-md-9">
            <button class="btn btn-sm btn-primary" id="btnExport" onclick="abrirModalNuevaCategoria()">Nueva Categoría</button>
            <button class="btn btn-sm btn-warning" id="btnExport" onclick="abrirModalNuevoConcepto()">Nuevo Concepto</button>
          </div>
        </div>
        <div class="row">
          <table id="listaTabla" class="table table-bordered table-sm table-responsive" style="width:100%">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($categorias as $claveCategoria => $categoria) :
                $conceptos = $modelConceptoGasto->listaPorCategoria($categoria["idcategoriagasto"]);
              ?>
                <tr data-tt-id="<?php echo $claveCategoria ?>" class="bg-light">
                  <td><b><?php echo $categoria["nombre"] ?></b></td>
                  <td></td>
                  <td>
                    <button class="btn btn-light btn-sm" type="button" onclick="abrirModalEditarCategoria('<?php echo $categoria['idcategoriagasto'] ?>','<?php echo $categoria['nombre'] ?>');" data-toggle="tooltip" title="Editar"><i class="fas fa-pencil-alt"></i></button>
                    <button class="btn btn-sm btn-primary" type='button' <?php echo ($categoria["estatus"] == 0) ? "style='display:none'" : "" ?> id="desactivarCategoria<?php echo $categoria["idcategoriagasto"] ?>" onclick="desactivarCategoria('<?php echo $categoria['idcategoriagasto']; ?>');" data-toggle="tooltip" title="Desactivar"><i class='fas fa-trash fa-sm'></i></button>
                    <button class="btn btn-sm btn-warning" type='button' <?php echo ($categoria["estatus"] == 1) ? "style='display:none'" : "" ?> id="activarCategoria<?php echo $categoria["idcategoriagasto"] ?>" onclick="activarCategoria('<?php echo $categoria['idcategoriagasto']; ?>');" data-toggle="tooltip" title="Activar"><i class='fas fa-trash-restore fa-sm'></i></button>
                  </td>
                </tr>
                <?php if ($conceptos) : ?>
                  <?php foreach ($conceptos as $claveConcepto => $concepto) : ?>
                    <tr data-tt-id="cat-<?php echo $claveConcepto ?>" data-tt-parent-id="<?php echo $claveCategoria ?>">
                      <td><?php echo $concepto["nombre"] ?></td>
                      <td><?php echo $categoria["nombre"] ?></td>
                      <td>
                        <button class="btn btn-light btn-sm" type="button" onclick="abrirModalEditarConcepto('<?php echo $concepto['idconceptogasto'] ?>','<?php echo $categoria['idcategoriagasto'] ?>','<?php echo $concepto['nombre'] ?>');" data-toggle="tooltip" title="Editar"><i class="fas fa-pencil-alt"></i></button>
                        <button class="btn btn-sm btn-primary categoriaDesactivar-<?php echo $categoria['idcategoriagasto'] ?>" type='button' <?php echo ($concepto["estatus"] == 0) ? "style='display:none'" : "" ?> id="desactivarConcepto<?php echo $concepto["idconceptogasto"] ?>" onclick="desactivarConcepto('<?php echo $concepto['idconceptogasto']; ?>');" data-toggle="tooltip" title="Desactivar" <?php echo ($categoria["estatus"] == 0) ? "disabled" : "" ?>><i class='fas fa-trash'></i></button>
                        <button class="btn btn-sm btn-warning categoriaActivar-<?php echo $categoria['idcategoriagasto'] ?>" type='button' <?php echo ($concepto["estatus"] == 1) ? "style='display:none'" : "" ?> id="activarConcepto<?php echo $concepto["idconceptogasto"] ?>" onclick="activarConcepto('<?php echo $concepto['idconceptogasto']; ?>');" data-toggle="tooltip" title="Activar" <?php echo ($categoria["estatus"] == 0) ? "disabled" : "" ?>><i class='fas fa-trash-restore fa-sm'></i></button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Nueva Categoría -->
  <div class="modal fade" id="modalNuevaCategoria" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <form method="POST" action="../controller/CategoriasGasto/Insertar.php">
          <div class="modal-header">
            <h5 class="modal-title" id="modalAdministrarCategoria">Nueva Categoría<h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-2">
                <label>Tipo Gasto:</label>
              </div>
              <div class="col-md-10">
                <label><?php echo $tipoGastoNombre ?></label>
              </div>
            </div>
            <div class="row">
              <div class="col-md-2">
                <label>Nombre:</label>
              </div>
              <div class="col-md-10">
                <input type="text" class="form-control form-control-sm" name="nombre" id="nuevaCategoriaNombre">
              </div>
            </div>
            <br>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="tipoGasto" value="<?php echo $tipoGastoId ?>">
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-sm btn-primary"></i>Guardar</button>
          </div>
      </div>
      </form>
    </div>
  </div>
  <!-- Modal Nueva Categoría -->
  <!-- Modal Editar Categoría -->
  <div class="modal fade" id="modalEditarCategoria" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <form method="POST" action="../controller/CategoriasGasto/Actualizar.php">
          <div class="modal-header">
            <h5 class="modal-title" id="modalAdministrarCategoria">Editar Categoría<h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-2">
                <label>Tipo Gasto:</label>
              </div>
              <div class="col-md-10">
                <label><?php echo $tipoGastoNombre ?></label>
              </div>
            </div>
            <div class="row">
              <div class="col-md-2">
                <label>Nombre:</label>
              </div>
              <div class="col-md-10">
                <input type="text" class="form-control form-control-sm" name="nombre" id="editarCategoriaNombre">
              </div>
            </div>
            <br>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="tipoGasto" value="<?php echo $tipoGastoId ?>">
            <input type="hidden" name="id" id="editarCategoriaId" required>
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-sm btn-primary"></i>Guardar</button>
          </div>
      </div>
      </form>
    </div>
  </div>
  <!-- Modal Editar Categoría -->
  <!-- Modal Nuevo Concepto -->
  <div class="modal fade" id="modalNuevoConcepto" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <form method="POST" action="../controller/ConceptosGasto/Insertar.php">
          <div class="modal-header">
            <h5 class="modal-title" id="modalAdministrarCategoria">Nuevo Concepto<h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-2">
                <label>Tipo Gasto:</label>
              </div>
              <div class="col-md-10">
                <label><?php echo $tipoGastoNombre ?></label>
              </div>
            </div>
            <div class="row">
              <div class="col-md-2">
                <label>Categoría:</label>
              </div>
              <div class="col-md-10">
                <select class="form-control form-control-sm" name="categoria" id="nuevoConceptoCategoria" required>
                  <?php foreach ($categorias as $categoriaData) : ?>
                    <option value="<?php echo $categoriaData["idcategoriagasto"] ?>"><?php echo $categoriaData["nombre"] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-md-2">
                <label>Nombre:</label>
              </div>
              <div class="col-md-10">
                <input type="text" class="form-control form-control-sm" name="nombre" id="nuevoConceptoNombre">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="tipoGasto" value="<?php echo $tipoGastoId ?>">
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-sm btn-primary"></i>Guardar</button>
          </div>
      </div>
      </form>
    </div>
  </div>
  <!-- Modal Nuevo Concepto -->
  <!-- Modal Editar Concepto -->
  <div class="modal fade" id="modalEditarConcepto" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <form method="POST" action="../controller/ConceptosGasto/Actualizar.php">
          <div class="modal-header">
            <h5 class="modal-title">Editar Concepto<h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-2">
                <label>Tipo Gasto:</label>
              </div>
              <div class="col-md-10">
                <label><?php echo $tipoGastoNombre ?></label>
              </div>
            </div>
            <div class="row">
              <div class="col-md-2">
                <label>Categoría:</label>
              </div>
              <div class="col-md-10">
                <select class="form-control form-control-sm" name="categoria" id="editarConceptoCategoria" required>
                  <?php foreach ($categorias as $categoriaData) : ?>
                    <option value="<?php echo $categoriaData["idcategoriagasto"] ?>"><?php echo $categoriaData["nombre"] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-md-2">
                <label>Nombre:</label>
              </div>
              <div class="col-md-10">
                <input type="text" class="form-control form-control-sm" name="nombre" id="editarConceptoNombre">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="tipoGasto" value="<?php echo $tipoGastoId ?>">
            <input type="hidden" name="id" id="editarConceptoId" required>
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-sm btn-primary"></i>Guardar</button>
          </div>
      </div>
      </form>
    </div>
  </div>
  <!-- Modal Editar Concepto -->

  <script type="text/JavaScript">
    $(document).ready(function(){
      $('#listaTabla').treetable('expandAll');
    });
    $("#listaTabla").treetable({ 
      expandable: true 
    });

    function abrirModalNuevaCategoria() {
      $("#nuevaCategoriaNombre").val();
      $("#modalNuevaCategoria").modal({
        show: true,
        keyboard: false,
        backdrop: 'static'
      });
    }

    function abrirModalEditarCategoria(id,nombre) {
      $("#editarCategoriaId").val(id);
      $("#editarCategoriaNombre").val(nombre);
      $("#modalEditarCategoria").modal({
        show: true,
        keyboard: false,
        backdrop: 'static'
      });
    }

  function abrirModalNuevoConcepto() {
    $("#nuevoConceptoNombre").val();
    $("#modalNuevoConcepto").modal({
      show: true,
      keyboard: false,
      backdrop: 'static'
    });
  }

  function abrirModalEditarConcepto(id,categoriaId,nombre) {
    $("#editarConceptoId").val();
    $("#editarConceptoCategoria").val();
    $("#editarConceptoNombre").val();

    $("#editarConceptoId").val(id);
    $("#editarConceptoCategoria").val(categoriaId);
    $("#editarConceptoNombre").val(nombre);
    $("#modalEditarConcepto").modal({
        show: true,
        keyboard: false,
        backdrop: 'static'
    });
  }

  function activarCategoria(id) {
    var id = id;
    $.ajax({
      type: "POST",
      url: "../../controller/CategoriasGasto/Activar.php",
      data: {
        id: id
      },
      success: function(data) {
        $("#desactivarCategoria" + id).show();
        $("#activarCategoria" + id).hide();
        alertify.success("Activación de categoría exitosa");
        $(".categoriaDesactivar-"+id).each(function(){
          $(this).prop("disabled",false);
          $(this).show();
        });
        $(".categoriaActivar-"+id).each(function(){
          $(this).hide();
          $(this).prop("disabled",false);
        });
      }
    });
  }

  function desactivarCategoria(id) {
    var id = id;
    $.ajax({
      type: "POST",
      url: "../../controller/CategoriasGasto/Desactivar.php",
      data: {
        id: id
      },
      success: function(data) {
        $("#activarCategoria" + id).show();
        $("#desactivarCategoria" + id).hide();
        alertify.message("Desactivación de categoría exitosa");

        $(".categoriaActivar-"+id).each(function(){
          $(this).prop("disabled",true);
          $(this).show();
        });
        $(".categoriaDesactivar-"+id).each(function(){
          $(this).prop("disabled",true);
          $(this).hide();
        });
      }
    });
  }

  function activarConcepto(id) {
    var id = id;
    $.ajax({
      type: "POST",
      url: "../../controller/ConceptosGasto/Activar.php",
      data: {
        id: id
      },
      success: function(data) {
        $("#desactivarConcepto" + id).show();
        $("#activarConcepto" + id).hide();
        alertify.success("Activación de concepto exitosa");
      }
    });
  }

  function desactivarConcepto(id) {
    var id = id;
    $.ajax({
      type: "POST",
      url: "../../controller/ConceptosGasto/Desactivar.php",
      data: {
        id: id
      },
      success: function(data) {
        $("#activarConcepto" + id).show();
        $("#desactivarConcepto" + id).hide();
        alertify.message("Desactivación de concepto exitosa");
      }
    });
  }
</script>