<?php
include_once('Medoo.php');

use Medoo\Medoo;

class ModelEmpleado
{
    var $base_datos;

    function __construct()
    {
        $this->base_datos = new Medoo();
    }
    //obtener todos los empelados
    function obtenerTodosEmpleados()
    {
        $sql = $this->base_datos->select("empleados", "*");
        return $sql;
    }
    //obtener por id
    function obtenerEmpleadoPorId($id)
    {
        $sql = $this->base_datos->get("empleados", "*", ["idempleado" => $id]);
        return $sql;
    }

    // agregar
    function agregarEmpleado($nombre, $tipoEmpleadoId, $sueldoBase, $infonavit, $zonaId)
    {
        $this->base_datos->insert("empleados", [
            "nombre" => $nombre,
            "tipo_empleado_id" => $tipoEmpleadoId,
            "sueldo_base" => $sueldoBase,
            "infonavit" => $infonavit,
            "zona_id" => $zonaId
        ]);
        return $this->base_datos->id();
    }

    function actualizarEmpleado($id, $nombre, $tipoEmpleadoId,  $sueldoBase, $infonavit,  $estatus, $zonaId)
    {
        $this->base_datos->update("empleados", [
            "nombre" => $nombre,
            "tipo_empleado_id" => $tipoEmpleadoId,
            "sueldo_base" => $sueldoBase,
            "infonavit" => $infonavit,
            "estatus" => $estatus,
            "zona_id" => $zonaId
        ], ["idempleado" => $id]);
    }


    function eliminarEmpleado($id)
    {
        $this->base_datos->update("empleados", ["estatus" => 0], ["idempleado" => $id]);
    }

    function verificarNombre($nombre, $zonaId)
    {
        $resultado = $this->base_datos->count("empleados", ["nombre" => $nombre, "zona_id" => $zonaId]);
        return $resultado > 0; // Devuelve verdadero si ya existe un empleado con el mismo nombre, de lo contrario, devuelve falso.
    }

    function obtenerEmpleadosZonaEstatus($zonaId, $estatus)
    {
        $sql = "SELECT idempleado, e.nombre, tipo_empleado_id, z.nombre zona_nombre,  sueldo_base, infonavit, ruta_id, r.clave_ruta , te.nombre tipo_empleado, usuario_id, e.estatus 
        from empleados e 
        left join tipos_empleado te on e.tipo_empleado_id=te.idtipoempleado
        left join rutas r on e.ruta_id= r.idruta
        left join zonas z on e.zona_id= z.idzona
        where e.estatus='$estatus' ";
        if ($zonaId && $zonaId != 0) {
            $sql .= " and e.zona_id=$zonaId";
        }
        return $this->base_datos->query($sql);
    }

    function obtenerEmpleadosEstatus($estatus)
    {
        $sql = "SELECT idempleado, e.nombre, tipo_empleado_id, z.nombre zona_nombre,  sueldo_base, infonavit, ruta_id, r.clave_ruta , te.nombre tipo_empleado, usuario_id, e.estatus 
        from empleados e 
        left join tipos_empleado te on e.tipo_empleado_id=te.idtipoempleado
        left join rutas r on e.ruta_id= r.idruta
        left join zonas z on e.zona_id= z.idzona
        where e.estatus='$estatus' ";
        return $this->base_datos->query($sql);
    }

    function obtenerTiposEmpleados()
    {
        $sql = $this->base_datos->query("SELECT * FROM tipos_empleado")->fetchAll(PDO::FETCH_ASSOC);;
        return $sql;
    }

    function obtenerEmpleadosConUsuarios()
    {
        $sql = $this->base_datos->select("empleados", [
            "[>]usuarios" => ["usuario_id" => "id"]
        ], [
            "empleados.idempleado",
            "empleados.nombre",
            "usuarios.id(usuario_id)",
            "usuarios.usuario"
        ]);
        return $sql;
    }

    function obtenerPrimerEmpleadoPorTipoZona($zonaId, $tipoEmpleadoId)
    {
        $sql = $this->base_datos->query("SELECT * FROM empleados 
        WHERE zona_id='$zonaId' AND 
        tipo_empleado_id = '$tipoEmpleadoId' 
        AND estatus=1 LIMIT 1")->fetchAll(PDO::FETCH_OBJ);

        // Verificar si se encontraron registros
        if (!empty($sql)) {
            return $sql[0];
        } else {
            return null; // Otra opción es devolver false, dependiendo de tus necesidades
        }
    }

    function obtenerEmpleadosPorTipoZona($zonaId, $tipoEmpleadoId)
    {

        $sql = $this->base_datos->query("SELECT * FROM empleados 
        WHERE zona_id='$zonaId' 
        AND tipo_empleado_id = '$tipoEmpleadoId'
        AND estatus=1")->fetchAll(PDO::FETCH_OBJ);

        return $sql;
    }

    function obtenerEmpleadosZonaTipoRuta($zonaId, $tipoRutaId, $tipoVendedorRuta,$empleadoId = 0)
    {
        //tipoVendedorRuta 1 = Vendedor principal
        //tipoVendedorRuta 2 = Vendedor secundario o ayudante
        $sql = "SELECT idempleado, upper(nombre) nombre, tipo_empleado_id 
        FROM empleados 
        WHERE zona_id = $zonaId and estatus = 1 ";
        if ($tipoRutaId == 1) {//Pipa mostrar los vendedores de pipas de la zona
            if($tipoVendedorRuta == 1){
                $sql .= " AND tipo_empleado_id = 1 ";//Vendedores principales
            }else{
                $sql .= " AND tipo_empleado_id = 3 ";//Ayudantes
            }
        }elseif ($tipoRutaId == 2 || $tipoRutaId == 3) {   // para las rutas cilindreras los vendedores de cilindros
            $sql .= " AND tipo_empleado_id = 5 ";
        }elseif($tipoRutaId == 4 || $tipoRutaId == 5){
            $sql .= " AND tipo_empleado_id = 6";
        }
        if($empleadoId != 0 && isset($empleadoId) && $empleadoId != ""){
            $sql .= " OR idempleado = $empleadoId";
        }
        $sql .= " ORDER BY nombre";

        return $this->base_datos->query($sql)->fetchAll(PDO::FETCH_OBJ);

    }

}
