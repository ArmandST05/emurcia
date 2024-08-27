<?php
	include('../../model/ModelLocalidad.php');
	$modelLocalidad = new ModelLocalidad();
	include('../../model/ModelZona.php');
	$modelZona = new ModelZona();

	$zonaId = 1;
	$municipioId = "";
	echo("ZonaId ".$zonaId);
	echo("MunicipioId ".$municipioId);
	$totalInserciones = 0;

	if($zonaId && $municipioId){
		$localidadesMunicipio = $modelLocalidad->listaPorMunicipio($municipioId);
		$eliminarMunicipio = $modelZona->eliminarLocalidadesZonaMunicipio($zonaId,$municipioId);
		
		foreach($localidadesMunicipio as $localidad){
			$modelZona->insertarZonaLocalidad($zonaId,$localidad['idlocalidad']);
			echo("Insertada en zona ".$zonaId." localidad ".$localidad['idlocalidad']." ".$localidad['nombre']);
			$totalInserciones++;
		}	
	}
	
	echo("Total inserciones ".$totalInserciones);
?>