<?php
$modelLogin = new ModelLogin();
$tipoUsuario = (empty($_GET['tipoUsuario'])) ? "general" : $_GET['tipoUsuario'];
if ($tipoUsuario == "superusuarios") $usuarios = $modelLogin->listaSuperUsuarios();
else $usuarios = $modelLogin->listaUsuarios();
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Usuarios</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Usuarios</h1>
  <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" href="index.php?action=usuarios/nuevo.php">Nuevo</a>
</div>
<div class="row">
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Header - Dropdown -->
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Lista de usuarios</h6>
      </div>
      <!-- Card Body -->
      <div class="card-body">
        <?php if ($tipoUsuario == "general") : ?>
          <button class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm" onclick="confirmarAcceso()">Mostrar Super Usuarios</button>
        <?php else : ?>
          <a class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm" href="index.php?action=usuarios/index.php&tipoUsuario=general">Mostrar Usuarios Generales</a>
        <?php endif; ?>
        <table id="listaUsuarios" class="table table-bordered table-sm table-responsive" style="width:100%">
          <thead>
            <tr>
              <th>Usuario</th>
              <?php if ($tipoUsuario == "general") : ?>
                <th>Contraseña</th>
              <?php endif; ?>
              <th>Tipo Usuario</th>
              <th>Zona</th>
              <th>Empleado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($usuarios as $usuario) : ?>
              <tr>
                <td><?php echo $usuario["usuario"] ?></td>
                <?php if ($tipoUsuario == "general") : ?>
                  <td><?php echo "******" ?></td>
                <?php endif; ?>
                <td><?php echo $usuario["tipo_usuario_nombre"] ?></td>
                <td><?php echo $usuario["zona_nombre"] ?></td>
                <td><?php echo ($usuario["empleado_nombre"]) ? $usuario["empleado_nombre"]:"NO APLICA" ?></td>
                <td>
                  <?php if ($usuario["tipo_usuario"] != "mv") : ?>
                    <form action='index.php' method='GET'>
                      <input type='hidden' name='action' value='usuarios/editar.php'>
                      <input type='hidden' name='id' value='<?php echo $usuario["idusuario"] ?>'>
                      <button type='submit' class='btn btn-sm btn-light'><i class='fas fa-pencil-alt fa-sm'></i></i></button>
                    </form>
                  <?php endif; ?>
                  <button class='btn btn-sm btn-primary' type='button' <?php if ($usuario['tipo_usuario'] == "su") : ?> onclick="confirmarEliminar('<?php echo $usuario['idusuario']; ?>','<?php echo $usuario['tipo_usuario']; ?>');" <?php else : ?> onclick="eliminarUsuario('<?php echo $usuario['idusuario']; ?>','<?php echo $usuario['tipo_usuario']; ?>');" <?php endif; ?>>
                    <i class='fas fa-trash fa-sm'></i>
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script type="text/JavaScript">
  $(document).ready(function() {
  });

  $('#listaUsuarios').DataTable({
    "pageLength": 25,
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
    }
  });

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

  function confirmarEliminar(usuarioId,tipoUsuario){
    if(tipoUsuario == "su"){
      alertify.prompt('Eliminar Súper Usuario', 'Ingresa la contraseña para eliminar', ''
        , function(evt, value) { 
          let superpassword = "<?php echo $_SESSION["superpass"] ?>";
          if(value == superpassword){
            eliminarUsuario(usuarioId,tipoUsuario);
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
    else{
      eliminarUsuario(usuarioId,tipoUsuario);
    }
  }
  
  function eliminarUsuario(usuarioId,tipoUsuario) {
    alertify.confirm("¿Realmente desea eliminar el usuario seleccionado?",
        function() {
          $.ajax({
              type: "POST",
              url: "../controller/Login/EliminarUsuario.php",
              data: {
                id: usuarioId
              },
              success: function(data) {
                location.reload();
                alertify.success("Usuario eliminado exitosamente");
              }
            });
        },
        function() {
        })
      .set({
        title: "Eliminar Usuario"
      })
      .set({
        labels: {
          ok: 'Aceptar',
          cancel: 'Cancelar'
        }
      });
  }
</script>