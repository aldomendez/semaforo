<?php 
include "../inc/database.php";

if (isset($_GET['action']) && $_GET['action'] !== '') {
	if (function_exists($_GET['action'])) {
		try {
			$_GET['action']();
		} catch (Exception $e) {
			echo '{"error":true,"desc":"Exception in: Get:[' . $_GET['action'] . '] with message: ' . $e->getMessage() . '"}';
		}
	} else {
		echo '{"error":true,"desc":"Exception: Get->Function->' . $_GET['action'] . ', Do not exists"}';
	}
}


function getMachines()
{
	// updateMachinesMxOptix();
	$query = file_get_contents('sql/machines.sql');
	$DB = new MxApps();
	$DB->setQuery($query);
	$DB->exec();
	if ($DB->json() == "[]") {
		throw new Exception("No arrojo datos la base de datos", 1);
	} else {
		echo $DB->json();
	}	
}

function updateTables()
{

	// Obtenemos la ultima fecha de actualizacion
	$pastDateString = file_get_contents('lastUpdate.txt');
	// Obtenemos una hora que pueda ser leida por el sistema
	$past = strtotime($pastDateString);
	// Guardamos la hora en el log (para poder ver los accesos que hemos estado teniendo)
	logToFile($past);

	$actual = strtotime('now');
	logToFile($actual-$past);
	
	if (($actual - $past) > 300) {
		// Si y solo si ha pasado mas de los segundos configurados
		// Empezamos a actualizar los datos de mi tabla.
		$date = date("d-M-Y H:i");
		// Guardamos la fecha de la ultima actualizacion para que no se vuelva a pedir 
		// otra actualizacion antes de que termine esta.
		file_put_contents('lastUpdate.txt', $date);
		updateMachinesMxOptix();
	}
	// Resolvemos para el navegador
	echo "$pastDateString";
}

function updateMachinesMxOptix()
{
	// Obtengo la lista de las maquinas dadas de alta en el sistema
	$query = file_get_contents('sql/machines.pull.data.sql');
	$DB = new MxApps();
	$DB->setQuery($query . " where dbconnection = 'mxoptix'");
	$DB->exec();
	// echo($DB->rows);
	if ($DB->rows > 0){
		// Sabiendo que tengo elementos para iterar puedo crear la conexion
		$MO = new MxOptix();

		// Ahora si ya puedo ir sacando los datos de cada una de las maquinas
		// para sacar la informacion de la base de datp
		foreach ($DB->results as $key => $value) {
			// genero el query para la busqueda de datos
			$query = file_get_contents('sql/getInfoFromMxOptix.sql');
			$MO->setQuery($query);
			$MO->bind_vars(':db_id',$value['DB_ID']);
			$MO->bind_vars(':facility',$value['DBMACHINE']);
			$MO->bind_vars(':device',$value['DBDEVICE']);
			$MO->bind_vars(':test_dt',$value['DBDATE']);
			$MO->bind_vars(':table',$value['DBTABLE']);
			// logToFile($MO->query);
			$MO->exec();

			// Actualizo la informacion en la tabla nueva
			// Solo si tengo datos nuevos

			// TODO:
			// buscar la manera de encontrar unicamente datos nuevos

			if ( sizeof($MO->results) > 0 ) {
				// genero el query para la busqueda de datos
				$query = file_get_contents('sql/updateMachinesInSemaforo.sql');
				$DB->setQuery($query);
				$DB->bind_vars(':test_dt',$MO->results[0]['TEST_DT']);
				$DB->bind_vars(':update-date',$date = date("d-M-Y H:i"));
				$DB->bind_vars(':id',$value['ID']);
				// logToFile($DB->query);
				$DB->exec();
				// logToFile($value['ID'] . ',' .$value['DB_ID'] . ',' .'Num of fields '.$DB->affected());

				// $query = file_get_contents('machines.sql');
				// $MO->setQuery($query);
				// $MO->bind_vars(':item',$_GET['item']);
				// $MO->exec();
			}
		}
	}

}

function logToFile($content)
{
	file_put_contents('logs.txt', $content.PHP_EOL , FILE_APPEND);
}