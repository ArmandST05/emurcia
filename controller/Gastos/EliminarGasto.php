<?php
    include('../../model/ModelGasto.php');
    $modelGasto = new ModelGasto();
    
    $id = $_POST["id"];
    $modelGasto->eliminarGasto($id);
?> 