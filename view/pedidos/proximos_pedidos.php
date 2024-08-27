<?php
$modelZona = new ModelZona();
$modelPedido = new ModelPedido();
date_default_timezone_set('America/Mexico_City');

$meses = ["1" => "Enero", "2" => "Febrero", "3" => "Marzo", "4" => "Abril", "5" => "Mayo", "6" => "Junio", "7" => "Julio", "8" => "Agosto", "9" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];

$anio = date("Y") + 5;
$tipoPeriodicidad = (!empty($_GET['tipoPeriodicidad'])) ? $_GET['tipoPeriodicidad'] : "sistema";

$fechaInicial = (!empty($_GET['fechaInicial'])) ? $_GET['fechaInicial'] : date("Y-m-d");
$fechaFinal = (!empty($_GET['fechaFinal'])) ? $_GET['fechaFinal'] : date("Y-m-d");

if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc") {
  $zonaId = (isset($_GET['zona'])) ? $_GET['zona'] : "";
  $zonas = $modelZona->obtenerZonasTodas();
} else {
  $zonaId = $_SESSION['zonaId'];
}

if ($zonaId) {
  $zonaNombre = $modelZona->obtenerZonaId($zonaId);
  $zonaNombre = $zonaNombre["nombre"];
}

if ($tipoPeriodicidad == "sistema") {
  $proximosPedidos = $modelPedido->proximosPedidosSistemaZonaEntreFechas($zonaId, $fechaInicial, $fechaFinal);
} else {
  $proximosPedidos = $modelPedido->proximosPedidosManualZonaEntreFechas($zonaId, $fechaInicial, $fechaFinal);
}
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Pedidos</a> /
    <a href="#">Próximos Pedidos</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Próximos Pedidos</h1>
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
      <div class="card-body">
        <form action='index.php' method='GET'>
          <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc") : ?>
            <div class="row">
              <div class="col-md-1">
                <div class="form-group">
                  <label>Zona:</label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <select class="form-control form-control-sm" name="zona" required>
                    <option selected disabled>Seleciona opción</option>
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
            <div class="col-md-1">
              <div class="form-group">
                <label>Periodicidad:</label>
              </div>
            </div>
            <div class="col-md-5">
              <div class="form-group">
                <select class="form-control form-control-sm" name="tipoPeriodicidad" id="tipoPeriodicidad">
                  <option value="sistema" <?php echo ($tipoPeriodicidad == "sistema") ? "selected" : "" ?>>Calculada por sistema</option>
                  <option value="usuario" <?php echo ($tipoPeriodicidad == "usuario") ? "selected" : "" ?>>Calculada por usuario</option>
                </select>
              </div>
            </div>
            <div class="col-md-5">
              <button class="btn btn-sm btn-light" type="button" data-toggle="modal" data-target="#ayudaPeriodicidad" data-toggle="tooltip" title="Ayuda"><i class="far fa-question-circle"></i></button>
            </div>
          </div>
          <div class="row">
            <div class="col-md-1">
              <div class="form-group">
                <label>Desde:</label>
              </div>
            </div>
            <div class="col-md-5">
              <div class="form-group">
                <input class="form-control form-control-sm" type="date" name="fechaInicial" value="<?php echo $fechaInicial ?>" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-1">
              <div class="form-group">
                <label>Hasta:</label>
              </div>
            </div>
            <div class="col-md-5">
              <div class="form-group">
                <input class="form-control form-control-sm" type="date" name="fechaFinal" value="<?php echo $fechaFinal ?>">
              </div>
            </div>
          </div>
          <input type='hidden' name='action' id='action' value="pedidos/proximos_pedidos.php" />
          <div class="row">
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
  <!-- Lista de Próximos Pedidos -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Header -->
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Próximos Pedidos <?php echo $tipoPeriodicidad ?></h6>
      </div>
      <!-- Card Body -->
      <div class="card-body">
        <div class="row">
          <div class="col-md-4 offset-md-8">
            <?php if ($_SESSION["tipoUsuario"] == "u" || $_SESSION["tipoUsuario"] == "su") : ?>
              <button class="btn btn-sm btn-light" type="button" onclick="abrirModalEnviarSMS()" tooltip="Enviar Mensaje"><i class="fas fa-envelope"></i> Enviar Mensajes</button>
            <?php endif; ?>
            <button class="btn btn-sm btn-warning" id="btnExport"><i class="far fa-file-excel"></i> Exportar Excel</button>
          </div>
        </div>
        <hr class="my-4">
        <div class="row">
          <div class="alert alert-warning col-md-12" role="alert">
            Haz clic sobre los clientes a lo que desees enviar un SMS, después en el botón Enviar Mensajes.
          </div>
        </div>
        <div class="row">
          <div class="col">
            <table id="listaTabla" class="table table-bordered table-sm table-responsive">
              <thead>
                <tr>
                  <th>No. Cliente</th>
                  <th>Cliente</th>
                  <th>Dirección</th>
                  <th>Teléfono</th>
                  <th>Último Pedido</th>
                  <th>Próximo Pedido</th>
                  <th>Producto</th>
                  <!--<th>Mensajes Enviados</th>-->
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($proximosPedidos as $pedido) : ?>
                  <tr>
                    <td><?php echo $pedido["idclientepedido"] ?></td>
                    <td><?php echo $pedido["nombre"] ?></td>
                    <td><?php echo $pedido["direccion"] . " " . $pedido["colonia"] ?></td>
                    <td><?php echo $pedido["telefono"] ?></td>
                    <td><?php echo $pedido["fecha_ultimo_pedido"] ?></td>
                    <td><?php echo $pedido["fecha_proximo_pedido"] ?></td>
                    <td><?php echo $pedido["producto_nombre"] ?></td>
                    <!--<td></td>-->
                    <td><a class='btn btn-sm btn-warning historial' href="index.php?action=pedidos/index_cliente.php&clienteId=<?php echo $pedido['idclientepedido']; ?>" id='historial' data-toggle='tooltip' title='Historial'><i class='fas fa-list'></i></a></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Enviar SMS -->
<div class="modal fade" id="modalEnviarSMS" tabindex="-1" role="dialog" aria-labelledby="modalSms" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalSms">Enviar Mensaje SMS <h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <label id="totalClientesSeleccionados"><b>:</b></label>
          </div>
          <div class="col-md-6">
            <label id="totalClientesDescartados"><b>Clientes descartados :</b></label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <label>Mensaje:</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <textarea class="form-control form-control-sm" name="smsTexto" id="smsTexto"></textarea>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-6">
            <label><b>Nombre:</b></label>
          </div>
          <div class="col-md-6">
            <label><b>Teléfono:</b></label>
          </div>
        </div>
        <div id="detalleClientesSeleccionados" class="h-50 overflow-hidden">

        </div>
        <br>
        <div class="row">
          <div class="alert alert-warning col-md-12 text-xs" role="alert">
            Los teléfonos se descartan si tienen menos de 10 dígitos o si se superan los 50 números por petición.
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-sm btn-primary" onclick="enviarSMS()"><i class="fas fa-envelope"></i> Enviar SMS</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal Enviar SMS -->
<!-- Modal Información Periodicidad -->
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
<!-- Modal Información Periodicidad -->

<script type="text/JavaScript">
  var telefonos = [];
  $(document).ready(function(){
    var listaTabla = $('#listaTabla').DataTable({
      dom: 'Blfrtip',
      buttons: [
        { extend: 'selectAll', className: 'btn btn-sm bg-light text-dark' },
        { extend: 'selectNone', className: 'btn btn-sm bg-light text-dark' }
      ],
      select: {
        style: 'multi'
      },
      pageLength: 25,
      order: [[ 3, "asc" ]],
      language: {
        url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json",
        buttons: {
              selectAll: "Seleccionar todo",
              selectNone: "Deseleccionar todo"
          }
      }
    });
  });

  $('#listaTabla tbody').on( 'click', 'tr', function () {
    $(this).toggleClass('selected');
  } );

  function abrirModalEnviarSMS() {
    telefonos.length = 0;
    var totalSmsEnviar = 0;
    var totalSmsDescartados = 0;

    let selectedRows = $('#listaTabla').DataTable().rows('.selected');
    
    //Limpiar donde se muestran los teléfonos de clientes
    $("#detalleClientesSeleccionados").html(""); 
    var detalleCliente = "";

    selectedRows.every(function(){
      let telefono = "52"+parseFloat($('#listaTabla').DataTable().cell(this, 3).data());
      if(totalSmsEnviar <= 50 && telefono.trim().length == 12){
        let clienteId = $('#listaTabla').DataTable().cell(this, 0).data();
        let clienteNombre = $('#listaTabla').DataTable().cell(this, 1).data();
        telefonos.push({'correlationId':1,'destination':telefono,'clienteId':clienteId});

        detalleCliente = "<div class='row'><div class='col-md-6'><label>"+clienteNombre+"</label></div><div class='col-md-6'><label>"+telefono+"</label></div></div>";             
        $("#detalleClientesSeleccionados").append(detalleCliente); 
        totalSmsEnviar++;
      }
      else totalSmsDescartados++;
    });
    if(totalSmsEnviar > 0){
      $("#totalClientesDescartados").text("Clientes descartados: "+totalSmsDescartados).addClass("font-weight-bold");
      $("#totalClientesSeleccionados").text("Clientes SMS: "+totalSmsEnviar).addClass("font-weight-bold");
      $("#modalEnviarSMS").modal({
        show: true,
        keyboard: false,
        backdrop: 'static'
      });
    }
    else{
      alertify.error("Selecciona clientes con un teléfono válido");
      alertify.error("Se descartaron "+totalSmsDescartados + " clientes");
    }
  }

  function enviarSMS() {
    let mensaje = $("#smsTexto").val();
    let mensajeEnviado = false;

    if(mensaje.length <= 0) alertify.success("Ingresa un mensaje válido");
    else if(mensaje.length > 200) alertify.success("El mensaje no debe superar los 200 caracteres");
    else{
        telefonos.forEach(function(telefonoEnvio,index){
          telefono = telefonoEnvio.destination;
          registrarEnvioSMS(telefonoEnvio.clienteId,telefono,mensaje);
        });
      }
  }

  function registrarEnvioSMS(clienteId,telefono,contenido){
    $.ajax({
      type: "POST",
      url: "../controller/SmsEnviados/InsertarSms.php",
      data: {
        pedidoId: null,
        rutaId: null,
        clienteId: clienteId,
        tipoDireccionSmsId: 1, //DIRECCIÓN 1(ENVIADO), 2 (RECIBIDO O RESPUESTA)
        estatusSmsId: 2,//ESTATUS 1(ENVIADO),2(RECIBIDO),3(NO ENTREGADO)
        zonaId: '<?php echo $zonaId ?>',
        telefono: telefono,
        contenido: contenido,
        moduloEnvioId: 2 //MÓDULOS 1(PEDIDOS),2 (PRÓXIMOS PEDIDOS)
      },
      success: function(data) {
        alertify.success("Mensaje Enviado");
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alertify.error("Ha ocurrido un error en el envío");
      },
      complete: function(data) {
        $("#modalEnviarSMS").modal('hide');
      }
    });
  }

  $("#btnExport").click(function (e) {
    $("#listaTabla").btechco_excelexport({
          containerid: "listaTabla"
          , datatype: $datatype.Table
          , filename: 'proximosPedidosZona'
    });
  });

</script>