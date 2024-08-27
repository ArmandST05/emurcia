<?php
$modelZona = new ModelZona();

$zonas = $modelZona->obtenerZonasGas();
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Puntos por zona</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Puntos por zona</h1>
</div>
<!-- Content Row -->
<div class="row">
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">VALOR DE PUNTOS ACTUAL</h6>
      </div>
      <!-- Card Body -->
      <div class="card-body">
        <label>Captura el valor en pesos de un punto.</label>
        <table id="listaTabla" class="table table-bordered table-sm table-responsive" style="width:100%">
          <thead>
            <tr>
              <th>ZONA</th>
              <th colspan="2">CANTIDAD DE DINERO QUE VALE UN PUNTO</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($zonas as $zona) : ?>
              <tr class="bg-light">
                <td><?php echo $zona["nombre"] ?></td>
                <form action="../controller/Zonas/ActualizarValorPunto.php" method="POST">
                  <td>
                    <input type="number" class="form-control productos-input" min="0" step=".01" name="valorPunto" value="<?php echo $zona["valor_punto"] ?>" />
                    <input type="hidden" name="zonaId" value="<?php echo $zona["idzona"] ?>">
                  </td>
                  <td>
                    <input class="btn btn-primary btn-sm" type="submit" value="Actualizar">
                  </td>
                </form>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script type="text/JavaScript">
  $(document).ready(function(){
    $("#zonaId").select2({});
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
  /*$("#precio_kilo" ).change(function() {
    let precioKg = parseFloat($("#precio_kilo").val());
    $(".productos-input").each(function(){
      let capacidadProducto = parseFloat($(this).attr("data-capacidad"));
      var precioProducto = precioKg * capacidadProducto;
      $(this).val(parseFloat(precioProducto).toFixed(2));
    });
  });*/

</script>