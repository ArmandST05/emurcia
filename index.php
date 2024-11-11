<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">

    <title>Power Gas</title>

    <!-- Bootstrap core CSS -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sign-in.css" rel="stylesheet">

    <!-- JQuery -->
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery-1.12.1.min.js"></script>
    <script src="js/jquery-easing/jquery.easing.min.js"></script>

    <script src="js/jquery.btechco.excelexport.js"></script>
    <script src="js/jquery.base64.js"></script>

    <link rel="stylesheet" type="text/css" href="js/jquery-ui.css">
    <script type="text/javascript" src="js/jquery-ui.js"></script>
    <!-- JQuery -->

    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="../plugins/bootstrap/css/bootstrap.min.css" />
    <script type="text/javascript" src="../plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- Bootstrap -->
</head>

<body>
    <div class="wrapper fadeInDown">
        <div id="formContent">
            <!-- Tabs Titles -->

            <!-- Icon -->
            <div class="fadeIn first">
                <img src="images/logo.jpg" id="icon" alt="User Icon" />
            </div>

            <!-- Login Form -->
            <form method="post" name="contact" action="controller/Login/ValidaLogin.php">
                <input type="text" id="user" class="fadeIn second" name="username" placeholder="Usuario" required>
                <input type="password" id="pass" class="fadeIn third" name="password" placeholder="Contraseña" required>
                <input type="submit" class="fadeIn fourth" value="Acceder">
            </form>

            <!-- Remind Passowrd -->
            <div id="formFooter">
                <a class="underlineHover" href="http://v2technoconsulting.com/"> © Techno Consulting</a>
            </div>

        </div>
    </div>
    <!-- Modal Borrar caché -->
    <div class="modal fade" id="modalBorrarCache" tabindex="-1" role="dialog" aria-labelledby="modalBorrarCache" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBorrarCache">Si ya realizaste este proceso, ignora este mensaje.<h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                </div>
                <div class="modal-body nuevaNominaForm">
                    <div class="row">
                        <div class="col-md-12">
                            <label>**SI YA REALIZASTE ESTE PROCESO, IGNORA ESTE MENSAJE**<br>Debido a una nueva actualización te solicitamos eliminar la caché del navegador para evitar problemas.
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <img src="images/BorrarCache1.PNG" alt="Imagen 1">
                    </div>
                    <div class="row">
                        <img src="images/BorrarCache2.PNG" alt="Imagen 1">
                    </div>
                    <div class="row">
                        <img src="images/BorrarCache3.PNG" alt="Imagen 1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Borrar caché -->
</body>

</html>
<script>
        function repetirAlerta() {
  alert("Agregar qui el mensaje");
  setTimeout(repetirAlerta, 5 * 1000); // 5 segundos en milisegundos
}

repetirAlerta();

    $(document).ready(function() {
        /*$("#modalBorrarCache").modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });*/
    });
</script>