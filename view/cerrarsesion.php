<?php
session_start(); //Iniciamos la session para poder usar la variable $_SESSION["user"]
echo "<script> alert('Hasta luego ".$_SESSION["user"]."'); </script>"; //Mostramos mensaje de despedida
session_destroy(); //Destruimos la sesion
echo "<script> window.location.href = '/'; </script>"; //Redireccionamos al login
?>