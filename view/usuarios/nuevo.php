<?php
$modelZona = new ModelZona();
$modelLogin = new ModelLogin();
$modelEmpleado = new ModelEmpleado();

$zonas = $modelZona->obtenerTodas();
$tiposUsuario = $modelLogin->obtenerTiposUsuarioAsignables();
$empleados = $modelEmpleado->obtenerEmpleadosEstatus(1);
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="index.php?action=usuarios/index.php">Usuarios</a> /
    <a href="#">Nuevo</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Nuevo usuario</h1>
</div>
<div class="row">
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Header - Dropdown -->
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Nuevo usuario</h6>
      </div>
      <!-- Card Body -->
      <div class="card-body">
        <form method="post" name="contact" id="contact" action="../controller/Login/InsertarUsuario.php">
          <table border="0">
            <tr>
              <td>Tipo usuario: </td>
              <td>
                <select class="form-control form-control-sm" name="tipoUsuario" id="tipoUsuario" onChange="ocultarZona();">
                  <?php foreach ($tiposUsuario as $tipoUsuario) : ?>
                    <option value="<?php echo $tipoUsuario['clave'] ?>"><?php echo $tipoUsuario['nombre'] ?></option>
                  <?php endforeach; ?>
                </select>
              </td>
            </tr>
            <tr>
              <td>Usuario: </td>
              <td><input class="form-control form-control-sm" type="text" id="usuario" name="usuario" required /></td>
            </tr>
            <tr>
              <td>Contraseña: </td>
              <td><input class="form-control form-control-sm" type="password" id="password" name="password" required /></td>
            </tr>
            <tr id="zonaDatos">
              <td>Zona: </td>
              <td>
                <select class="form-control form-control-sm" name="zona[]" id="zona" required>
                  <?php foreach ($zonas as $dataZona) : ?>
                    <option value="<?php echo $dataZona['idzona'] ?>">
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
                    <option value="<?php echo $empleado['idempleado'] ?>">
                      <?php echo strtoupper($empleado["nombre"]) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </td>
            </tr>
          </table>
          <br />
          <input class="btn btn-sm btn-primary" type="submit" class="submit_btn" name="submit" id="submit" value="Guardar" />
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/JavaScript">
  $(document).ready(function() {
    ocultarZona();
    $("#empleado").select2({});
  });

  function ocultarZona(){
    if ($('#tipoUsuario').val() == 'u' || $('#tipoUsuario').val() == 've' || $('#tipoUsuario').val() == 'mp'){
      document.getElementById('zonaDatos').style.visibility='visible';

      if($('#tipoUsuario').val() == 'mp'){
        $("#zona").select2({
          multiple:true
        }); 
      }else{
        $("#zona").select2({
          multiple:false
        }); 
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
</script>