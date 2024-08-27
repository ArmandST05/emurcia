<?php
$modelZona = new ModelZona();
if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc") {
  $zonaId = (isset($_GET["zona"])) ? $_GET["zona"] : "";
  $zonas = $modelZona->listaTodas();
} else {
  $zona = $_SESSION["zona"];
  $zonaId = $_SESSION["zonaId"];
}
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Clientes Pedidos</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Clientes Pedidos</h1>
</div>
<?php if ($_SESSION['tipoUsuario'] == "su" || $_SESSION["tipoUsuario"] == "uc") : ?>
  <!-- Content Row -->
  <div class="row">
    <div class="col-xl-12 col-lg-12">
      <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Buscar</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
          <form action='index.php' method='GET'>
            <div class="row">
              <div class="col-md-1">
                <div class="form-group">
                  <label>Zona:</label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <select class="form-control form-control-sm" name="zona" id="zona">
                    <option value="" readonly>Selecciona zona</option>
                    <?php foreach ($zonas as $zonaData) : ?>
                      <option value="<?php echo $zonaData['idzona'] ?>" <?php echo ($zonaData['idzona'] == $zonaId) ? "selected" : "" ?>> <?php echo strtoupper($zonaData['nombre']) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div clas="col-md-2">
                <input type='hidden' name='action' id='action' value="clientes/index_pedido.php" />
                <input class="btn btn-primary btn-sm" type='submit' id='busqueda' value='Buscar'>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>
<!-- Content Row -->
<div class="row">
  <!-- Nuevo Pedido -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Header -->
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Lista Clientes
      </div>
      <!-- Card Header -->
      <!-- Card Body -->
      <?php if ($zonaId) : ?>
        <div class="card-body">
          <div class="row">
            <div class="col-md-2 offset-md-10">
              <button class="btn btn-sm btn-warning" id="btnExport"><i class="far fa-file-excel"></i> Exportar Excel</button>
              <button class="btn btn-sm btn-light" type="button" data-toggle="modal" data-target="#ayudaPeriodicidad" tooltip="Ayuda"><i class="far fa-question-circle"></i></button>
            </div>
          </div>
          <hr class="my-4">
          <?php if (!empty($zonaId)) : ?>
            <table id="listaTabla" class="table table-responsive table-bordered table-sm listaTabla">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Nombre</th>
                  <th>Dirección</th>
                  <th>Colonia</th>
                  <th>Teléfono</th>
                  <th>Último Pedido</th>
                  <th>Periodicidad Sistema</th>
                  <th>Próxima Entrega Sistema</th>
                  <th>Periodicidad Usuario</th>
                  <th>Próxima Entrega Usuario</th>
                  <th>Producto</th>
                  <th>Acción</th>
                </tr>
              </thead>
              <tbody>

              </tbody>
            </table>
          <?php endif; ?>
        </div>
      <?php endif; ?>
      <!-- MODAL EDITAR CLIENTE -->
      <div class="modal fade" id="modalEditarCliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Editar Cliente</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar" onclick="limpiarModalEditar()">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label class="col-form-label">Nombre:</label>
                  <input type="text" class="form-control form-control-sm" id="editarNombre">
                </div>
                <div class="form-group col-md-6">
                  <label class="col-form-label">Calle:</label>
                  <input type="text" class="form-control form-control-sm" id="editarCalle">
                </div>
                <div class="form-group col-md-6">
                  <label class="col-form-label">Número exterior:</label>
                  <input type="text" class="form-control form-control-sm" id="editarNumeroExterior">
                </div>
                <div class="form-group col-md-6">
                  <label class="col-form-label">Número interior:</label>
                  <input type="text" class="form-control form-control-sm" id="editarNumeroInterior">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label class="col-form-label">Colonia:</label>
                  <input type="text" class="form-control form-control-sm" id="editarLocalidad">
                </div>
                <div class="form-group col-md-6">
                  <label class="col-form-label">Municipio:</label>
                  <input type="text" class="form-control form-control-sm" id="editarMunicipio">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label class="col-form-label">Teléfono:</label>
                  <input type="text" class="form-control form-control-sm" id="editarTelefono">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label class="col-form-label">Entrega Sugerida Sistema:</label>
                  <input type="text" class="form-control form-control-sm" id="editarProximoSistema" readonly>
                </div>
                <div class="form-group col-md-6">
                  <label class="col-form-label">Próxima Entrega Usuario:</label>
                  <input type="date" class="form-control form-control-sm" id="editarProximoManual">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label class="col-form-label">Producto:</label>
                  <select class="form-control form-control-sm" id="editarProducto">
                    <option value="0" selected disabled>Selecciona un producto</option>
                    <option value="5">Cilindro 10KG</option>
                    <option value="1">Cilindro 20KG</option>
                    <option value="2">Cilindro 30KG</option>
                    <option value="10">Estacionario</option>
                    <option value="11">Fuga</option>
                    <option value="12">Pintura</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <input type="hidden" id="editarId">
              <button type="button" class="btn btn-sm btn-light" data-dismiss="modal" onclick="limpiarModalEditar()">Cerrar</button>
              <button type="button" class="btn btn-sm btn-primary" onclick="actualizar()">Guardar</button>
            </div>
          </div>
        </div>
      </div>
      <!-- MODAL EDITAR CLIENTE-->
      <!-- MODAL AYUDA PERIODICIDAD-->
      <div class="modal fade" id="ayudaPeriodicidad" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel"><i class="far fa-question-circle"></i> Periodicidad</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <h5>Sistema</h5>
              <p>Calcula la diferencia entre el primer y último pedido del cliente y se promedia entre el total de pedidos.</p>
              <hr class="my-4">
              <h5>Usuario</h5>
              <p>Calcula la diferencia entre el último pedido y el penúltimo pedido.
                Si se cambia la fecha del próximo pedido desde el módulo
                de clientes se calcula la diferencia entre la fecha que ingresó el usuario y la fecha del último pedido.
              </p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-sm btn-warning" data-dismiss="modal">Aceptar</button>
            </div>
          </div>
        </div>
      </div>
      <!-- MODAL AYUDA PERIODICIDAD -->
    </div>
  </div>
  <!-- Card Body -->
</div>

<script>
  $(document).ready(function() {
    var zonaId = "<?php echo $zonaId ?>";
    let permisoEliminar = "<?php echo (array_search('clientes.pedidos.eliminar', $_SESSION['permisos'])) ?>";
    let opcionesTabla = "<button class='btn btn-sm btn-warning historial' id='historial' type='button' data-toggle='tooltip' title='Historial'><i class='fas fa-list'></i></button>";
    if ("<?php echo $_SESSION['tipoUsuario'] ?>" == "su" || "<?php echo $_SESSION['tipoUsuario'] ?>" == "u") {
      opcionesTabla = opcionesTabla + "<button class='btn btn-sm btn-light editar' type='button' id='editar' data-toggle='tooltip' title='Editar'><i class='fas fa-pencil-alt'></i></button>";
    }

    if ("<?php echo $_SESSION['tipoUsuario'] ?>" == "su" || ("<?php echo $_SESSION['tipoUsuario'] ?>" == "u" && (permisoEliminar !== false || permisoEliminar != false))) {
      opcionesTabla = opcionesTabla + "<button class='btn btn-sm btn-primary eliminar' id='eliminar' type='button' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash'></i></button>";
    }

    var dataTable = $('#listaTabla').DataTable({
      "pageLength": 25,
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
      },
      "ordering": false,
      "processing": true,
      "serverSide": true,
      "ajax": {
        url: "../controller/ClientesPedidos/ObtenerPorZona.php", // json datasource
        type: "post", // method, by default get
        dataSrc: 'data',
        data: {
          zonaId: zonaId
        },
        error: function() { // error handling
          $(".lookup-error").html("");
          $("#lookup").append('<tbody class="employee-grid-error"><tr><th colspan="3">Ningún dato encontrado</th></tr></tbody>');
          $("#lookup_processing").css("display", "none");
        }
      },
      "columns": [{
          data: 'idclientepedido'
        },
        {
          data: 'nombre'
        },
        {
          data: 'direccion'
        },
        {
          data: 'colonia'
        },
        {
          data: 'telefono'
        },
        {
          data: 'fecha_ultimo_pedido'
        },
        {
          data: 'periodicidad_sistema'
        },
        {
          data: 'fecha_proximo_pedido_sistema'
        },
        {
          data: 'periodicidad'
        },
        {
          data: 'fecha_proximo_pedido_manual'
        },
        {
          data: 'producto_id'
        },
      ],
      "columnDefs": [{
        "targets": [11],
        "data": null,
        "defaultContent": opcionesTabla
      }]
    });

    $('#listaTabla tbody').on('click', '#editar', function() {
      limpiarModalEditar();
      var data = dataTable.row($(this).parents('tr')).data();
      $("#editarId").val(data["idclientepedido"]);
      $("#editarNombre").val(data["nombre"]);
      $("#editarDireccion").val(data["direccion"]);
      $("#editarTelefono").val(data["telefono"]);
      $("#editarProximoSistema").val(data["fecha_proximo_pedido_sistema"]);
      $("#editarProximoManual").val(data["fecha_proximo_pedido_manual"]);
      $("#editarProducto").val(data["producto_id"]);
      $("#modalEditarCliente").modal('show');
    });

    $('#listaTabla tbody').on('click', '#historial', function() {
      var data = dataTable.row($(this).parents('tr')).data();
      window.location.href = "index.php?action=pedidos/index_cliente.php&clienteId=" + data["idclientepedido"];
    });

    $('#listaTabla tbody').on('click', '#eliminar', function() {
      var data = dataTable.row($(this).parents('tr')).data();
      alertify.confirm("¿Realmente desea eliminar el cliente? Los datos no se podrán recuperar.",
          function() {
            $.ajax({
              type: "POST",
              url: "../controller/ClientesPedidos/EliminarCliente.php",
              data: {
                id: data["idclientepedido"]
              },
              success: function(data) {
                location.reload();
                alertify.success("Cliente eliminado");
              }
            });
          },
          function() {})
        .set({
          title: "Eliminar Cliente"
        })
        .set({
          labels: {
            ok: 'Aceptar',
            cancel: 'Cancelar'
          }
        });
    });

  });

  $("#btnExport").click(function(e) {
    window.open("../view/index.php?action=clientes/reporte_index_pedido.php&zona=" + '<?php echo $zonaId ?>', '_blank');
  });

  function limpiarModalEditar() {
    $("#editarId").val("");
    $("#editarNombre").val("");
    $("#editarDireccion").val("");
    $("#editarTelefono").val("");
    $("#editarProximoSistema").val("");
    $("#editarProximoManual").val("");
  }

  function actualizar(cliente) {
    var id = $("#editarId").val();
    var nombre = $("#editarNombre").val();
    var direccion = $("#editarDireccion").val();
    var telefono = $("#editarTelefono").val();
    var proximaFecha = $("#editarProximoManual").val();
    var producto = $("#editarProducto").val();

    if (id == "" || nombre == "" || direccion == "" || telefono == "" || proximaFecha == "" || producto == 0) {
      alertify.error("Ingresa todos los datos");
    } else {
      $.ajax({
        type: "POST",
        url: "../controller/ClientesPedidos/ActualizarCliente.php",
        data: {
          id: id,
          nombre: nombre,
          direccion: direccion,
          telefono: telefono,
          proximaFecha: proximaFecha,
          producto: producto
        },
        success: function(data) {
          alertify.success("Actualización Correcta");
          location.reload();
        }
      });
    }
  }

  function activar(id) {
    var id = id;
    $.ajax({
      type: "POST",
      url: "../controller/ClientesPedidos/ActivarCliente.php",
      data: {
        id: id
      },
      success: function(data) {
        $("#desactivar" + id).show();
        $("#activar" + id).hide();
        alertify.success("Activación de cliente exitosa");
      }
    });
  }

  function desactivar(id) {
    var id = id;
    $.ajax({
      type: "POST",
      url: "../controller/ClientesPedidos/BajaCliente.php",
      data: {
        id: id
      },
      success: function(data) {
        $("#activar" + id).show();
        $("#desactivar" + id).hide();
        alertify.message("Baja de cliente exitosa");
      }
    });
  }
</script>