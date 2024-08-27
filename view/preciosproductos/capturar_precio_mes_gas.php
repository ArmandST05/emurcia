<?php
$modelZona = new ModelZona();
$modelPrecioProducto = new ModelPrecioProducto();
$modelProducto = new ModelProducto();
$meses = ["1" => "Enero", "2" => "Febrero", "3" => "Marzo", "4" => "Abril", "5" => "Mayo", "6" => "Junio", "7" => "Julio", "8" => "Agosto", "9" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];
$mes = date("n");
$anio = date("Y");
$zonas = $modelZona->obtenerZonasPrecioGas();

$mesNombre = $meses[$mes];
$preciosZonasMes = $modelPrecioProducto->obtenerPrecioGasZonasMes($mes, $anio);

$productosArray = $modelProducto->obtenerCilindros();
if (!empty($productosArray)) {
  $productosArray = array_chunk($productosArray, 3);
}
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Precio Mes</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Precio Mes</h1>
</div>
<!-- Content Row -->
<div class="row">
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Capturar precio del mes</h6>
      </div>
      <!-- Card Body -->
      <div class="card-body">
        <form action="../controller/Productos/InsertarPrecioMesZonaGas.php" method="POST">
          <div class="row">
            <div class="col-lg-3">
              <div class="form-group">
                <label>Mes</label>
                <input class="form-control form-control-sm" type="text" value="<?php echo $mesNombre ?>" name="mes" readonly>
              </div>
            </div>
            <div class="col-lg-2">
              <div class="form-group">
                <label>Año</label>
                <input class="form-control form-control-sm" type="text" value="<?php echo $anio ?>" name="anio" readonly>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <label>Zona</label>
                <select class="form-control form-control-sm" name="zona" id="zona" onchange="obtenerRutas()">
                  <?php foreach ($zonas as $data) : ?>
                    <option value="<?php echo $data["idzona"] ?>"><?php echo strtoupper($data["nombre"]) ?></option>
                  <?php endforeach; ?>
                  ?>
                </select>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="form-group">
                <label>Ruta</label>
                <select class="form-control form-control-sm" name="ruta" id="ruta">
                  <option value="0">PRECIO GENERAL ZONA</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Precio por kilo</label>
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                  </div>
                  <input class="form-control" type="number" min="0" step=".01" name="precio_kilo" id="precio_kilo" onkeypress='validateKg(event)' required>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Precio por litro</label>
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                  </div>
                  <input class="form-control" type="number" min="0" step=".01" name="precio_litro" onkeypress='validateLt(event)' required>
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="card col-md-12">
              <div class="card-body">
                <p class="card-title">Precio de Productos </p>
                <?php foreach ($productosArray as $productos) : ?>
                  <div class="row">
                    <?php foreach ($productos as $producto) : ?>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label><?php echo $producto["nombre"] ?></label>
                          <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text">$</span>
                            </div>
                            <input type="number" class="form-control productos-input" type="number" min="0" step=".01" value="0" name="productos[<?php echo $producto['idproducto'] ?>]" id="producto-<?php echo $producto['idproducto']; ?>" data-capacidad="<?php echo $producto['capacidad']; ?>" required>
                          </div>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="alert alert-warning col-md-12" role="alert">
              Los precios que indiques se mostrarán al capturar ventas.
            </div>
          </div>
          <div class="row">
            <div class="col-md-2 offset-11">
              <div class="form-group">
                <input class="btn btn-primary btn-sm" type="submit" value="Guardar">
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Precios del mes <?php echo $mesNombre ?></h6>
      </div>
      <!-- Card Body -->
      <div class="card-body">
        <?php if (empty($preciosZonasMes)) : ?>
          <label>Captura el precio del mes.</label>
        <?php else : ?>
          <table id="listaTabla" class="table table-bordered table-sm table-responsive" style="width:100%">
            <thead>
              <tr>
                <th>Zona</th>
                <th>Ruta</th>
                <th>Precio Kg</th>
                <th>Precio Lts</th>
                <?php if ($_SESSION["tipoUsuario"] == "su") : ?><th>Acciones</th><?php endif; ?>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($preciosZonasMes as $zonaPrecio) : ?>
                <tr class="bg-light">
                  <td><b><?php echo $zonaPrecio["zona_nombre"] ?></b></td>
                  <td><b>GENERAL ZONA</b></td>
                  <td><b>$<?php echo $zonaPrecio["precio_kilo"] ?></b></td>
                  <td><b>$<?php echo $zonaPrecio["precio_litro"] ?></b></td>
                  <td></td>
                </tr>
                <?php
                $preciosRutasMes = $modelPrecioProducto->obtenerPrecioGasRutasZonaMes($mes, $anio,$zonaPrecio["zona_id"]);
                foreach ($preciosRutasMes as $ruta) : ?>
                  <tr>
                    <td></td>
                    <td><?php echo $ruta["ruta_nombre"] ?></td>
                    <td>$<?php echo $ruta["precio_kilo"] ?></td>
                    <td>$<?php echo $ruta["precio_litro"] ?></td>
                    <td></td>
                  </tr>
                <?php endforeach; ?>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script type="text/JavaScript">
  $(document).ready(function(){
    obtenerRutas();
  });
  function validateLt(evt) {
    var theEvent = evt || window.event;
    var key = theEvent.which;
    key = String.fromCharCode( key );
    var regex = /[0-9]|\./;
    var regex2 =   /[ -~]/;
    if( regex2.test(key) && !regex.test(key) ) {
        theEvent.returnValue = false;
        if(theEvent.preventDefault){
            theEvent.preventDefault();
        }
    }  
  }
  function validateKg(evt) {
    var theEvent = evt || window.event;
    var key = theEvent.which;
    key = String.fromCharCode( key );
    var regex = /[0-9]|\./;
    var regex2 =   /[ -~]/;
    if( regex2.test(key) && !regex.test(key) ) {
        theEvent.returnValue = false;
        if(theEvent.preventDefault){
            theEvent.preventDefault();
        }
    }  
  }

  //Calcular precios de cilindros en base a precio por kilo
  $("#precio_kilo" ).change(function() {
    let precioKg = parseFloat($("#precio_kilo").val());
    $(".productos-input").each(function(){
      let capacidadProducto = parseFloat($(this).attr("data-capacidad"));
      var precioProducto = precioKg * capacidadProducto;
      $(this).val(parseFloat(precioProducto).toFixed(2));
    });
  });

  function obtenerRutas(){
    let zonaId = $("#zona").val();
    $("#ruta").empty().append('<option value="0" selected>PRECIO GENERAL ZONA</option>');
    $.ajax({
      data: { zonaId : zonaId },
      type: "GET",
      url: '../controller/Rutas/ObtenerPorZona.php', 
      dataType: "json",
      success: function(data){
        $.each(data,function(key, ruta) {
          let estatus_ruta = "";
          if(ruta.estatus == 0) estatus_ruta = "(INACTIVO)"; 
          $("#ruta").append('<option value='+ ruta.idruta + '>'+ ruta.clave_ruta +' '+estatus_ruta+'</option>');
        });
      },
      error: function(data) {
        alertify.error('Ha ocurrido un error al cargar las rutas de la zona.');
      }
    });
  }
</script>