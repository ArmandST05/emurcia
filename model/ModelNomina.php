<?php
include_once('Medoo.php');

use Medoo\Medoo;

class ModelNomina
{
    var $base_datos;

    function __construct()
    {
        $this->base_datos = new Medoo();
    }

    function insertar($zonaId, $fechaInicial, $fechaFinal)
    {
        $this->base_datos->insert("nominas", [
            "fecha_inicio" => $fechaInicial,
            "fecha_fin" => $fechaFinal,
            "zona_id" => $zonaId
        ]);
        return $this->base_datos->id();
    }

    function insertarEmpleadoNomina($nominaId, $empleadoId, $tipoEmpleadoId, $rutaId, $tipoGananciaRutaId, $cantidadNormal, $cantidadDescuento, $metaCumplidaId, $diasLaborados, $faltas, $extras, $sueldoBaseDia, $sueldoBaseTotal, $comisiones, $infonavit, $total, $banco, $efectivo)
    {
        $this->base_datos->insert("nomina_empleados", [
            "nomina_id" => $nominaId,
            "empleado_id" => $empleadoId,
            "tipo_empleado_id" => $tipoEmpleadoId,
            "ruta_id" => $rutaId,
            "tipo_ganancia_ruta_id" => $tipoGananciaRutaId,
            "cantidad_normal" => $cantidadNormal,
            "cantidad_descuento" => $cantidadDescuento,
            "meta_cumplida_id" => $metaCumplidaId,
            "dias_laborados" => $diasLaborados,
            "faltas" => $faltas,
            "extras" => $extras,
            "sueldo_base_dia" => $sueldoBaseDia,
            "sueldo_base_total" => $sueldoBaseTotal,
            "comisiones" => $comisiones,
            "infonavit" => $infonavit,
            "total" => $total,
            "banco" => $banco,
            "efectivo" => $efectivo
        ]);
        return $this->base_datos->id();
    }

    function actualizarValorEmpleado($nominaId, $empleadoId, $columnaNombre, $valor)
    {
        $sql = $this->base_datos->update("nomina_empleados", [
            $columnaNombre => $valor
        ], ["nomina_id[=]" => $nominaId, "empleado_id[=]" => $empleadoId]);

        return $sql->rowCount();
    }

    function actualizarNomina($nominaId, $total, $banco, $efectivo, $observaciones)
    {
        $sql = $this->base_datos->update("nominas", [
            'total' => $total,
            'banco' => $banco,
            'efectivo' => $efectivo,
            'observaciones' => $observaciones,
        ], ["idnomina[=]" => $nominaId]);

        return $sql->rowCount();
    }

    function obtenerNominaId($id)
    {
        $sql = $this->base_datos->query("SELECT n.*,z.nombre as zona_nombre
        FROM nominas n
        INNER JOIN zonas z ON z.idzona = n.zona_id
        WHERE n.idnomina='$id'")->fetchAll(PDO::FETCH_OBJ);
        if ($sql) {
            return $sql[0];
        } else {
            return null;
        }
    }

    function obtenerNominasZonaMes($zonaId, $mesAnio)
    {
        $sql = "SELECT n.*,z.nombre AS zona_nombre 
        FROM nominas n
        INNER JOIN zonas z ON z.idzona = n.zona_id
        WHERE DATE_FORMAT(n.fecha_inicio,'%Y-%m') = '$mesAnio' ";
        if ($zonaId) {
            $sql .= " AND n.zona_id='$zonaId' ";
        }
        $sql .= " ORDER BY n.fecha_inicio DESC";;
        return $this->base_datos->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    function obtenerDatosNominaPorTipoEmpleado($zonaId, $fechaInicio, $fechaFin, $tipoEmpleado)
    {
        $sql = $this->base_datos->query("SELECT e.idempleado, e.tipo_empleado_id, e.nombre, 
            e.sueldo_base, e.infonavit, 
            ifnull(sum(cantidad),0)  cantidad_lts, ifnull(sum(cantidad)*0.524, 0)  cantidad_kg,
            sum(ifnull(cantidad_venta_contado,0)  + ifnull(cantidad_venta_credito,0)  ) lts_descuento,
            ifnull(sum(cantidad),0)  -  sum( ifnull(cantidad_venta_contado, 0)  + ifnull(cantidad_venta_credito, 0) ) normal
            FROM  empleados e  
            LEFT JOIN venta_empleados ve  on e.idempleado = ve.empleado_id 
            LEFT JOIN ventas v  on v.idventa = ve.venta_id 
                AND fecha >= '$fechaInicio' AND fecha <= '$fechaFin' 
            LEFT JOIN detalles_venta dv on dv.venta_id = v.idventa
            LEFT JOIN rutas ru on ve.empleado_id = ru.vendedor1
            WHERE e.zona_id =$zonaId 
            AND e.tipo_empleado_id = $tipoEmpleado 
            AND e.estatus=1
            GROUP BY e.idempleado")->fetchAll(PDO::FETCH_OBJ);
        return $sql;
    }

    function obtenerDatosNominaVentaPorEmpleado($zonaId, $fechaInicio, $fechaFin, $empleadoId)
    {
        /*$sql = $this->base_datos->query("SELECT e.idempleado,ifnull(sum(cantidad),0) cantidad_lts, 
            ifnull(sum(cantidad)*0.524, 0) cantidad_kg,
            sum(ifnull(cantidad_venta_contado,0)  + ifnull(cantidad_venta_credito,0)) lts_descuento,
            sum((ifnull(dv.total_venta_credito,0) + ifnull(dv.descuento_total_venta_credito,0))/ dv.precio) lts_credito,
            ifnull(sum(cantidad),0) - sum( ifnull(cantidad_venta_contado, 0)  + ifnull(cantidad_venta_credito, 0)) normal,
            sum((ifnull(dv.total_venta_contado,0) + ifnull(dv.descuento_total_venta_contado,0))/ dv.precio) lts_contado,
            sum(ifnull(dv.total_rubros_venta, 0)) total_cilindros
            FROM  empleados e  
            LEFT JOIN venta_empleados ve  on e.idempleado = ve.empleado_id 
            LEFT JOIN ventas v  on v.idventa = ve.venta_id 
                AND fecha >= '$fechaInicio' AND fecha <= '$fechaFin' 
            LEFT JOIN detalles_venta dv on dv.venta_id = v.idventa
            WHERE e.zona_id =$zonaId 
            AND e.idempleado = $empleadoId 
            GROUP BY e.idempleado")->fetchAll(PDO::FETCH_OBJ);
*/
        //Para el descuento crédito es necesario sumar el total $ de crédito y el total $ de descuento de venta crédito entre el precio para obtener cuánto fue de descuento del día, ese total se puede sumar al descuento contado en lts (se debe verificar que las zonas lo capturen correctamente)
        $sql = $this->base_datos->query("SELECT e.idempleado,ifnull(sum(cantidad),0) cantidad_lts, 
            ifnull(sum(cantidad)*0.524, 0) cantidad_kg,
            sum(ifnull(cantidad_venta_contado,0)  + (ifnull(descuento_total_venta_credito,0) + ifnull(dv.total_venta_credito,0))/ dv.precio) lts_descuento_credito,
            sum(ifnull(dv.cantidad,0) - ifnull(cantidad_venta_contado,0)  - (ifnull(descuento_total_venta_credito,0) + ifnull(dv.total_venta_credito,0))/ dv.precio) total_lts_normal,
            sum(ifnull(dv.total_rubros_venta, 0) * ifnull(p.capacidad, 0))/30 total_cilindros
            FROM  empleados e  
            LEFT JOIN venta_empleados ve  on e.idempleado = ve.empleado_id 
            LEFT JOIN ventas v  on v.idventa = ve.venta_id 
                AND fecha >= '$fechaInicio' AND fecha <= '$fechaFin' 
            LEFT JOIN detalles_venta dv on dv.venta_id = v.idventa
            LEFT JOIN productos p on dv.producto_id = p.idproducto
            WHERE e.zona_id =$zonaId 
            AND e.idempleado = $empleadoId 
            GROUP BY e.idempleado")->fetchAll(PDO::FETCH_OBJ);

        if ($sql) {
            return $sql[0];
        } else {
            return null;
        }
    }

    function obtenerTiposEmpleadosNomina($nominaId)
    {
        $sql = $this->base_datos->query("SELECT te.* 
        FROM nomina_empleados ne
        INNER JOIN tipos_empleado te ON te.idtipoempleado = ne.tipo_empleado_id
        WHERE ne.nomina_id = '$nominaId' 
        GROUP BY te.idtipoempleado ")->fetchAll(PDO::FETCH_OBJ);
        return $sql;
    }

    function obtenerEmpleadosNominaTipo($nominaId, $tipoEmpleadoId, $tipoGananciaRuta = null, $rutaId = 0)
    {

        $sql = "SELECT ne.*,e.nombre AS nombre
            FROM empleados e
            INNER JOIN nomina_empleados ne ON ne.empleado_id = e.idempleado
            WHERE ne.nomina_id='$nominaId' AND 
            ne.tipo_empleado_id = '$tipoEmpleadoId' ";
        if ($tipoGananciaRuta) {
            $sql .= " AND ne.tipo_ganancia_ruta_id = '$tipoGananciaRuta' ";
        }
        if ($rutaId && $rutaId != 0) {
            $sql .= " AND ne.ruta_id = '$rutaId' ";
        }
        $sql .= " ORDER BY ruta_id";

        $data = $this->base_datos->query($sql)->fetchAll(PDO::FETCH_OBJ);
        if ($tipoEmpleadoId == 2) { //Solo retornar un gerente
            if ($data) {
                return $data[0];
            } else {
                return null;
            }
        } else {
            return $data;
        }
    }

    function obtenerRutasPorTipoNomina($nominaId, $tipoRutaId)
    {
        $sql = $this->base_datos->query("SELECT r.*
            FROM nomina_empleados ne
            INNER JOIN rutas r ON r.idruta = ne.ruta_id
            AND r.tipo_ruta_id = '$tipoRutaId'
            WHERE ne.nomina_id = '$nominaId' 
            GROUP BY r.idruta")->fetchAll(PDO::FETCH_OBJ);
        return $sql;
    }

    function eliminar($id)
    {
        $sql = $this->base_datos->delete("nominas", ["idnomina[=]" => $id]);
        return $sql->rowCount();
    }


    function obtenerValorFondo($fondoId)
{
    $sql = $this->base_datos->get("fondo", "valor_fondo", ["id" => $fondoId]);
    return $sql;
}
function insertarValorFondo($valorFondo)
{
    $data = [
        "valor_fondo" => $valorFondo,
    ];

    $sql = $this->base_datos->insert("fondo", $data);
    return $sql->rowCount(); // Devuelve el número de filas afectadas
}
public function obtenerFondos()
{
    // Ejecuta la consulta para obtener todos los registros de la tabla "fondo"
    $fondos = $this->base_datos->select('fondo', [
        'id',  'valor_fondo'
    ]);

    // Retorna el resultado
    return $fondos;
}
public function eliminarFondo($fondoId)
    {
        $sql = $this->base_datos->delete("fondo", [
            "id" => $fondoId
        ]);

        return $sql->rowCount(); // Devuelve el número de filas afectadas
    }
}
