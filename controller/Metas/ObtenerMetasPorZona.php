<?php 
include('../../model/ModelMeta.php'); 
/* Variable para llamar al método del modelo */
$modelMeta = new ModelMeta();


$zonaId = $_GET['zona'];

$metas = $modelMeta->obtenerMetasPorZona($zonaId);

// Inicializar la variable $htmlTabla con la apertura de la tabla
$htmlTabla = '<table class="table table-bordered table-sm listaTabla">';
$htmlTabla .= '<thead>';
$htmlTabla .= '<tr>';
$htmlTabla .= '<th>ID</th>';
$htmlTabla .= '<th>Nombre</th>';
$htmlTabla .= '<th>Descripción</th>';
$htmlTabla .= '<th>Zona</th>';
$htmlTabla .= '<th>Meta#1</th>';
$htmlTabla .= '<th>Meta#2</th>';
$htmlTabla .= '<th>Meta#3</th>';
$htmlTabla .= '<th>Meta#4</th>';
$htmlTabla .= '<th>Meta#5</th>';
$htmlTabla .= '<th>Tipo de empleado</th>';
$htmlTabla .= '<th>Descuento/Completo</th>';
$htmlTabla .= '<th>Tipo venta</th>';
$htmlTabla .= '<th>Ruta</th>';
$htmlTabla .= '<th>Fecha de creación</th>';
$htmlTabla .= '<th>Acción</th>';
$htmlTabla .= '</tr>';
$htmlTabla .= '</thead>';
$htmlTabla .= '<tbody>';

foreach ($metas as $meta) {

   
    $htmlTabla .= '<tr>';
    $htmlTabla .= '<td>' . $meta['idmetazona'] . '</td>';
    $htmlTabla .= '<td>' . $meta['nombre'] . '</td>';
    $htmlTabla .= '<td>' . $meta['descripcion'] . '</td>';
    $htmlTabla .= '<td>' . $meta['zona_nombre'] . '</td>';
    $htmlTabla .= '<td>' . $meta['meta1'] . '</td>';
    $htmlTabla .= '<td>' . $meta['meta2'] . '</td>';
    $htmlTabla .= '<td>' . $meta['meta3'] . '</td>';
    $htmlTabla .= '<td>' . $meta['meta4'] . '</td>';
    $htmlTabla .= '<td>' . $meta['meta5'] . '</td>';
    $htmlTabla .= '<td>' . (($meta['tipo_empleado_id']) ? $meta['tipo_empleado_nombre'] : "N/A") . '</td>';
    $htmlTabla .= '<td>' . (($meta['para_descuento']) == 0 ? "Completo": "Descuento" ). '</td>';
    $htmlTabla .= '<td>' . $meta['tipo_ganancia_ruta_nombre']. '</td>';
    $htmlTabla .= '<td>' . ($meta['ruta_id'] ? $meta['ruta_nombre']:"N/A") . '</td>';
    $htmlTabla .= '<td>' . $meta['created_at'] . '</td>';
    $htmlTabla .= '<td>';
    $htmlTabla .= '<button class="btn btn-sm btn-light" type="button" data-toggle="tooltip" title="Editar" onclick="editarMeta(' . $meta['idmetazona'] . ')">';
    $htmlTabla .= '<i class="fas fa-pencil-alt"></i>';
    $htmlTabla .= '</button>';
    $htmlTabla .= '<button class="btn btn-sm btn-primary eliminar" type="button" data-toggle="tooltip" title="Eliminar" onclick="confirmarEliminarMeta(' . $meta['idmetazona'] . ')">';
    $htmlTabla .= '<i class="fas fa-trash"></i>';
    $htmlTabla .= '</button>';
    $htmlTabla .= '</td>';
    $htmlTabla .= '</tr>';
    $htmlTabla .= '<tr>';
    if($meta['tipo_empleado_id']==2){
        $ambitoComisionesGerente = $modelMeta->obtenerMetaGerente($meta['idmetazona']);
        $htmlTabla .= '<td colspan="4">Comisiones: ';
        foreach($ambitoComisionesGerente as $ambito){
            $htmlTabla .= $ambito->nombre . ' ';
        }

        $htmlTabla .= '</td>';
    }else{
        $htmlTabla .= '<td colspan="4">Comisiones</td>';
    }
    
    $htmlTabla .= '<td>' . $meta['comision1'] . '</td>';
    $htmlTabla .= '<td>' . $meta['comision2'] . '</td>';
    $htmlTabla .= '<td>' . $meta['comision3'] . '</td>';
    $htmlTabla .= '<td>' . $meta['comision4'] . '</td>';
    $htmlTabla .= '<td>' . $meta['comision5'] . '</td>';
    $htmlTabla .= '<td colspan="5"></td>';

    $htmlTabla .= '</tr>';

}

$htmlTabla .= '</tbody>';
$htmlTabla .= '</table>';


echo $htmlTabla
?>


