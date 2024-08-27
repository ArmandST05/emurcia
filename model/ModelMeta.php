<?php
include_once('Medoo.php');

use Medoo\Medoo;

class ModelMeta
{
    var $base_datos;

    function __construct()
    {
        $this->base_datos = new Medoo();
    }

    function obtenerMetasPorZona($zonaId)
    {
        $sql = "SELECT  mz.*, z.nombre as zona_nombre, te.nombre tipo_empleado_nombre, r.clave_ruta as ruta_nombre,
            tgr.nombre AS tipo_ganancia_ruta_nombre
            FROM metas_zonas mz
            LEFT JOIN zonas z on z.idzona = mz.zona_id
            LEFT JOIN tipos_empleado te on te.idtipoempleado = mz.tipo_empleado_id
            LEFT JOIN rutas r on r.idruta = mz.ruta_id 
            INNER JOIN tipos_ganancia_ruta tgr ON tgr.idtipogananciaruta = mz.tipo_ganancia_ruta_id";
        if ($zonaId) {
            $sql .= " WHERE mz.zona_id='$zonaId'";
        }

        return $this->base_datos->query($sql);
    }

    function obtenerMetasTipoEmpleado($zonaId, $tipoEmpleado, $tipoGananciaRuta,$descuento,$rutaId = 0)
    {
        $query = "SELECT mz.*,
            tgr.nombre AS tipo_ganancia_ruta_nombre 
            FROM metas_zonas mz
            INNER JOIN tipos_ganancia_ruta tgr ON tgr.idtipogananciaruta = mz.tipo_ganancia_ruta_id
            WHERE mz.zona_id = $zonaId AND mz.tipo_empleado_id = $tipoEmpleado 
            AND mz.para_descuento = $descuento ";
            if($tipoGananciaRuta){
                $query.= " AND mz.tipo_ganancia_ruta_id = $tipoGananciaRuta ";
            }
            if($rutaId && $rutaId != 0){
                $query.= " AND mz.ruta_id = $rutaId ";
            }
        $sql = $this->base_datos->query($query)->fetchAll(PDO::FETCH_OBJ);

        // Verificar si se encontraron registros
        if (!empty($sql)) {
            if ($tipoEmpleado == 2) { //Gerente
                return $sql;
            } else {
                return $sql[0];
            }
        } else {
            return null; // Otra opción es devolver false, dependiendo de tus necesidades
        }
    }

    function obtenerTiposDeVentas()
    {
        $sql = $this->base_datos->query("SELECT * 
        from tipo_ventas");
        return $sql;
    }

    function insertarMeta($nombre, $descripcion, $tipoEmpleado, $zona, $ruta, $tipoGananciaRuta, $meta1, $meta2, $meta3, $meta4, $meta5, $comision1, $comision2, $comision3, $comision4, $comision5, $descuento)
    {
        $sql = $this->base_datos->insert("metas_zonas", [
            "nombre" => $nombre,
            "descripcion" => $descripcion,
            "tipo_empleado_id" => $tipoEmpleado,
            "zona_id" => $zona,
            "ruta_id" => $ruta,

            "meta1" => $meta1,
            "meta2" => $meta2,
            "meta3" => $meta3,
            "meta4" => $meta4,
            "meta5" => $meta5,

            "comision1" => $comision1,
            "comision2" => $comision2,
            "comision3" => $comision3,
            "comision4" => $comision4,
            "comision5" => $comision5,

            "para_descuento" => $descuento,
            "tipo_ganancia_ruta_id" => $tipoGananciaRuta,
        ]);
        return $this->base_datos->id();
    }

    function insertarDetalleGerente($zonaId, $metaId, $ambitoComisionId)
    {
        $sql = $this->base_datos->query("DELETE FROM metas_gerentes 
        WHERE meta_id = '$metaId'");

        $sql = $this->base_datos->query("INSERT INTO metas_gerentes(meta_id, zona_id, ambito_comision_id)
        values ($metaId, $zonaId, $ambitoComisionId)");

        return $this->base_datos->id();
    }

    function obtenerMetaGerente($id)
    {
        $sql = $this->base_datos->query("SELECT ambito_comision_id, ac.nombre 
        FROM metas_gerentes mg 
        INNER JOIN ambitos_comision ac on mg.ambito_comision_id = ac.idambitocomision 
        WHERE meta_id=$id")->fetchAll(PDO::FETCH_OBJ);
        return $sql;
    }

    function eliminarMeta($idmeta)
    {
        $this->base_datos->query("DELETE from metas_gerentes where meta_id = $idmeta;");
        $sql = $this->base_datos->query("DELETE from metas_zonas where idmetazona = $idmeta;");

        return $sql;
    }

    function validarExisteMeta($tipoEmpleado, $descuento, $zonaId, $rutaId,$tipoGananciaRuta)
    {
        $sql = "SELECT count(*) total FROM metas_zonas 
        WHERE tipo_empleado_id = $tipoEmpleado 
        AND para_descuento = $descuento 
        AND tipo_ganancia_ruta_id = $tipoGananciaRuta 
        AND zona_id = $zonaId ";
        if ($rutaId) {
            $sql .= " and ruta_id=$rutaId ";
        }

        $result = $this->base_datos->query($sql)->fetchAll(PDO::FETCH_OBJ);

        return $result[0];
    }

    function obtenerMetaPorId($id)
    {
        $sql = $this->base_datos->query("SELECT  mz.*, z.nombre zona, r.clave_ruta ruta
            from metas_zonas mz
            LEFT JOIN zonas z on z.idzona=mz.zona_id
            LEFT JOIN rutas r on r.idruta = mz.ruta_id
            where idmetazona='$id'")->fetchAll(PDO::FETCH_ASSOC);
        return $sql[0];
    }

    function actualizarMeta($id, $nombre, $descripcion, $tipoEmpleado, $meta1, $meta2, $meta3, $meta4, $meta5, $comision1, $comision2, $comision3, $comision4, $comision5, $ruta, $tipoGananciaRuta, $descuento)
    {
        $sql = $this->base_datos->update("metas_zonas", [
            "nombre" => $nombre,
            "descripcion" => $descripcion,
            "tipo_empleado_id" => $tipoEmpleado,
            "ruta_id" => $ruta,

            "meta1" => $meta1,
            "meta2" => $meta2,
            "meta3" => $meta3,
            "meta4" => $meta4,
            "meta5" => $meta5,

            "comision1" => $comision1,
            "comision2" => $comision2,
            "comision3" => $comision3,
            "comision4" => $comision4,
            "comision5" => $comision5,

            "para_descuento" => $descuento,
            "tipo_ganancia_ruta_id" => $tipoGananciaRuta,
        ], ["idmetazona[=]" => $id]);

        return $this->base_datos->id();
    }
}
