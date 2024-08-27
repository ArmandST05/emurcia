<script>
  function confirmarAcceso() {
    alertify.prompt('Editar Super Usuarios', 'Ingresa la contraseña', '', function(evt, value) {
        let superpassword = "<?php echo $_SESSION["superpass"] ?>";
        if (value == superpassword) {

        } else {
          alertify.error("Contraseña errónea");
          window.location.href = "index.php?action=usuarios/index.php";
        }
      }, function() {
        alertify.error("Acción no autorizada");
        window.location.href = "index.php?action=usuarios/index.php";
      })
      .set({
        type: 'password',
        labels: {
          ok: 'Aceptar',
          cancel: 'Cancelar'
        }
      });
  }
</script>
<?php
$modelZona = new ModelZona();
$modelLogin = new ModelLogin();
$modelEmpleado = new ModelEmpleado();

$usuarioId = $_GET["id"];
$usuario = $modelLogin->obtenerUsuarioId($usuarioId);
if($usuario["tipo_usuario"] == "mp"){
  $usuarioZonas = array_column($modelLogin->obtenerUsuarioZonas($usuarioId), 'zona_id');
}else{
  $usuarioZonas = [];
}
$zonas = $modelZona->obtenerTodas();
$tiposUsuario = $modelLogin->obtenerTiposUsuarioAsignables();
$empleados = $modelEmpleado->obtenerEmpleadosEstatus(1);

if (!$usuario) {
  echo '<script>
          alert("No se puede modificar este usuario");
          window.location.href = "index.php?action=usuarios/index.php";
        </script>';
} elseif ($usuario["tipo_usuario"] == "su") {
  echo '<script>
          confirmarAcceso();
        </script>';
}
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="index.php?action=usuarios/index.php">Usuarios</a> /
    <a href="#">Editar</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Editar Usuario</h1>
</div>
<div class="row">
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Header - Dropdown -->
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Modificar usuario</h6>
      </div>
      <!-- Card Body -->
      <div class="card-body">
        <form method="post" name="contact" id="contact" action="../controller/Login/ActualizarUsuario.php">
          <table border="0">
            <tr>
              <td>Tipo usuario:</td>
              <td>
                <select class="form-control form-control-sm" name="tipoUsuario" id="tipoUsuario" onChange="ocultarZona();">
                  <?php if ($usuario["tipo_usuario"] != "su") : ?>
                    <?php foreach ($tiposUsuario as $tipoUsuario) : ?>
                      <option value="<?php echo $tipoUsuario['clave'] ?>" <?php echo (strcmp($usuario["tipo_usuario"], $tipoUsuario['clave']) == 0) ? 'selected' : '' ?>><?php echo $tipoUsuario['nombre'] ?></option>
                    <?php endforeach; ?>

                  <?php else : ?>
                    <option value="su" <?php echo (strcmp($usuario["tipo_usuario"], "su") == 0) ? 'selected' : '' ?>>Administrador</option>
                  <?php endif; ?>
                </select>
              </td>
            </tr>
            <tr>
              <td>Usuario: </td>
              <td><input class="form-control form-control-sm" type="text" id="usuario" name="usuario" value="<?php echo $usuario["usuario"] ?>" /></td>
            </tr>
            <tr>
              <td>Contraseña: (Sólo se actualizará si escribes algo)</td>
              <td><input class="form-control form-control-sm" type="password" id="password" name="password" /></td>
            </tr>
            <?php if ($usuario["tipo_usuario"] != "su") : ?>
              <tr id="zonaDatos">
                <td>Zona: </td>
                <td>
                  <select class="form-control form-control-sm" name="zona[]" id="zona" required>
                    <?php foreach ($zonas as $dataZona) : ?>
                      <option value="<?php echo $dataZona['idzona'] ?>" <?php echo (in_array($usuario["zona_id"],$usuarioZonas)) ? "selected" : "" ?>>
                        <?php echo strtoupper($dataZona["nombre"]) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </td>
              </tr>
              <tr id="empleadosDatos">
                <td>Empleados: </td>
                <td>
                  <select class="form-control form-control-sm" name="empleado" id="empleado">
                    <option value="0" selected disabled>Seleccionar opción</option>
                    <?php foreach ($empleados as $empleado) : ?>
                      <option value="<?php echo $empleado['idempleado'] ?>" <?php echo ($usuario["empleado_id"] == $empleado['idempleado']) ? "selected" : "" ?>>
                        <?php echo strtoupper($empleado["nombre"]) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </td>
              </tr>
            <?php endif; ?>
          </table>
          <br>
          <input type="hidden" name="id" value="<?php echo $usuario["idusuario"] ?>">
          <input type="submit" class="btn btn-sm btn-primary" name="submit" id="submit" value="Guardar" />
          <input type="hidden" name="superpass" id="superpass" value="<?php echo $_SESSION["superpass"] ?>">
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/JavaScript">
  $(document).ready(function() {
    $("#empleado").select2({});
    ocultarZona();
  });

  function ocultarZona(){
    if ($('#tipoUsuario').val() == 'u' || $('#tipoUsuario').val() == 've' || $('#tipoUsuario').val() == 'mp'){
      document.getElementById('zonaDatos').style.visibility='visible';

      if($('#tipoUsuario').val() == 'mp'){
        $("#zona").select2({
          multiple:true
        }).val(<?php echo json_encode($usuarioZonas)?>).trigger('change');

      }else{
        $("#zona").select2({
          multiple:false
        }).val(<?php echo json_encode($usuario["zona_id"])?>).trigger('change');; 
      }

    }else{
      document.getElementById('zonaDatos').style.visibility='hidden';
    }
    if ($('#tipoUsuario').val() == 've'){
      document.getElementById('empleadosDatos').style.visibility='visible';
    }else{
      document.getElementById('empleadosDatos').style.visibility='hidden';
    }
  }

  function confirmarAcceso(){
    alertify.prompt('Mostrar Super Usuarios', 'Ingresa la contraseña para acceder', ''
      , function(evt, value) { 
        let superpassword = "<?php echo $_SESSION["superpass"] ?>";
        if(value == superpassword){
            window.location.href = "index.php?action=usuarios/index.php&tipoUsuario=superusuarios";
        }else{
          alertify.error("Contraseña errónea");
        }
      }
      , function() { 
      })
      .set({
          type: 'password',
          labels: {
              ok: 'Aceptar',
              cancel: 'Cancelar'
          }
      });
  }
</script>