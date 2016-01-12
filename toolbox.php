<?php 

if ( $_SERVER['REMOTE_ADDR'] != '192.168.1.147'){
    $local = false;
    include "../inc/database.php";
} else {
    $local = true;
}

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
	global $local;
	$query = file_get_contents('sql/machines.sql');
	if($local){
        $json = file_get_contents('filecache.txt');
    } else {
        $DB = new MxApps();
        $DB->setQuery($query);
        $DB->exec();
        $json = $DB->json();
    }
	if ($json == "[]") {
		throw new Exception("No arrojo datos la base de datos", 1);
	} else {
		file_put_contents('filecache.txt', $json);
		echo $json;
	}
	if (!$local) {
		$DB->close();
	}
}

function getSpecificMachine()
{
	// updateMachinesMxOptix();
	$query = file_get_contents('sql/machines.sql');
	$DB = new MxApps();
	$DB->setQuery($query. " and id = '" . $_GET['ID'] ."'");
	$DB->exec();
	if ($DB->json() == "[]") {
		throw new Exception("No arrojo datos la base de datos", 1);
	} else {
		echo $DB->json();
	}
	$DB->close();
}

function updateTables()
{
	update_every(300,'mxoptix');
	update_every(300,'mxapps');
	update_every(300,'prodmx'); //10 Min


}

function update_every($segundos,$conexion)
{
	$inicio = date("d-M-Y H:i:s");
	logToFile(sprintf("Llamado: , %s,",$inicio));
	// Obtenemos la ultima fecha de actualizacion
	if (file_exists('lastUpdate.' . $conexion .'.txt')) {
		$pastDateString = file_get_contents('lastUpdate.' . $conexion .'.txt');
	} else {
		// Genero una fecha en el pasado por que se supone que no he corrido ninguna vez
		$pastDateString = date("d-M-Y H:i:s") - 10;
	}
	// Obtenemos una hora que pueda ser leida por el sistema
	$past = strtotime($pastDateString);
	// Guardamos la hora en el log (para poder ver los accesos que hemos estado teniendo)
	// logToFile($past);

	$actual = strtotime('now');
	// logToFile(date("d-M-Y H:i"));

	$lockFileName = $conexion . '.lock';

	if (($actual - $past) > ($segundos * 2) &&!file_exists($lockFileName)) {
		// Si ya pasaron 2 ciclos y no se ha ejecutado el query resetea el LOCK
		// para que se pueda ejecutar el query de nuevo.
		unlink($lockFileName);
	}

	if (($actual - $past) > $segundos && !file_exists($lockFileName)) {
		echo "Ejecutando: ". $conexion . " a los " . ($actual - $past) . "s " . PHP_EOL;
		// Si y solo si ha pasado mas de los segundos configurados
		// Empezamos a actualizar los datos de mi tabla.
		$date = date("d-M-Y H:i");
		// Guardamos la fecha de la ultima actualizacion para que no se vuelva a pedir 
		// otra actualizacion antes de que termine esta.
		file_put_contents('lastUpdate.' . $conexion .'.txt', $date);
		file_put_contents($lockFileName, '1');
		// En esta parte es donde se hace la actualizacion en si
		if($conexion == 'prodmx'){
			updateMachines($conexion,$lockFileName);
		}else {
			updateMachinesTogether($conexion,$lockFileName);
		}
		unlink($lockFileName);
	}
	// Resolvemos para el navegador
	// echo "$pastDateString";
}


function updateMachinesTogether($connection, $lockFileName){
	// Obtengo la lista de las maquinas dadas de alta en el sistema
	$inicio = date("d-M-Y H:i:s");
	// logToFile(sprintf("Inicio, %s ",$inicio));
	$machinesQuery = file_get_contents('sql/machines.pull.data.sql');
	$DB = new MxApps();
	$DB->setQuery($machinesQuery . " where dbconnection = '".$connection."' and active = 1");
	$DB->exec();
	// echo($DB->rows);
	$connections = array(
		'mxoptix' => 'MxOptix',
		'mxapps'  => 'MxApps',
		'prodmx' => 'Prod',
		'dare_mrc'=>'MRC'
	);
	file_put_contents($lockFileName, $DB->rows . PHP_EOL , FILE_APPEND);
	if ($DB->rows > 0){
		// la coneccion se hace a la tabla especifica en la que vamos a buscar
		$MO = new $connections[$connection]();
		logToFile($connections[$connection]);
		// Ahora si ya puedo ir sacando los datos de cada una de las maquinas
		// para sacar la informacion de la base de dato
		echo "StartTime:" . date("d-M-Y H:i");

		$subQuerys = array();
		$infoQuery = file_get_contents('sql/getInfo.sql');
		foreach ($DB->results as $key => $value) {
			$remaining = $DB->rows - 1;
			file_put_contents($lockFileName, $remaining . ": " . $value['DB_ID'] . PHP_EOL , FILE_APPEND);
			// genero el query para la busqueda de datos
			$MO->setQuery($infoQuery);
			$MO->bind_vars(':db_id',$value['DB_ID']);
			$MO->bind_vars(':id',$value['ID']);
			$MO->bind_vars(':facility',$value['DBMACHINE']);
			$MO->bind_vars(':device',$value['DBDEVICE']);
			$MO->bind_vars(':test_dt',$value['DBDATE']);
			$MO->bind_vars(':table',$value['DBTABLE']);
			// Put this new Query in an array
			array_push($subQuerys, $MO->query);
		}
		// Merge all array alements with a UNION ALL statement and assign as the new Query
		$mergedQuerys = implode(PHP_EOL." union all ". PHP_EOL, $subQuerys);
		file_put_contents('mergedQuerys'. "." .$connections[$connection], $mergedQuerys);
		$MO->setQuery($mergedQuerys);
		// Lo tengo que ejecutar con todos los querys unidos UNICAMENTE
		$MO->exec();
		

		if ( sizeof($MO->results) > 0 ) {
			$updateQuery = file_get_contents('sql/updateMachinesInSemaforo.sql');

			foreach ($MO->results as $key => $value) {
				$DB->setQuery($updateQuery);
				$DB->bind_vars(':test_dt',$value['TEST_DT']);
				$DB->bind_vars(':update-date',$date = date("d-M-Y H:i"));
				$DB->bind_vars(':id',$value['ID']);

				echo $DB->query . PHP_EOL;
				$DB->exec();
				
			}

			echo "EndtTime:" . date("d-M-Y H:i");
		}
		$MO->close();
	}

	$DB->close();

	$final = date("d-M-Y H:i:s");
	logToFile(sprintf("[x] Completado: inicio:%s, final:%s", $inicio, $final));
}


function updateMachines($connection, $lockFileName){
	// Obtengo la lista de las maquinas dadas de alta en el sistema
	$inicio = date("d-M-Y H:i:s");
	// logToFile(sprintf("Inicio, %s ",$inicio));
	$machinesQuery = file_get_contents('sql/machines.pull.data.sql');
	$DB = new MxApps();
	$DB->setQuery($machinesQuery . " where dbconnection = '".$connection."' and active = 1 and lastrun < SYSDATE - 10/(24*60) ORDER BY lastrun asc");
	$DB->exec();
	// echo($DB->rows);
	$connections = array(
		'mxoptix' => 'MxOptix',
		'mxapps'  => 'MxApps',
		'prodmx' => 'Prod',
		'dare_mrc'=>'MRC'
	);
	file_put_contents($lockFileName, $DB->rows . PHP_EOL , FILE_APPEND);
	if ($DB->rows > 0){
		// la coneccion se hace a la tabla especifica en la que vamos a buscar
		$MO = new $connections[$connection]();
		logToFile($connections[$connection]);
		// Ahora si ya puedo ir sacando los datos de cada una de las maquinas
		// para sacar la informacion de la base de datp
		foreach ($DB->results as $key => $value) {
			$remaining = $DB->rows - 1;
			file_put_contents($lockFileName, $remaining . ": " . $value['DB_ID'] . PHP_EOL , FILE_APPEND);
			// genero el query para la busqueda de datos
			$infoQuery = file_get_contents('sql/getInfo.sql');
			$MO->setQuery($infoQuery);
			$MO->bind_vars(':db_id',$value['DB_ID']);
			$MO->bind_vars(':id',$value['ID']);
			$MO->bind_vars(':facility',$value['DBMACHINE']);
			$MO->bind_vars(':device',$value['DBDEVICE']);
			$MO->bind_vars(':test_dt',$value['DBDATE']);
			$MO->bind_vars(':table',$value['DBTABLE']);
			
			$MO->exec();

 			if ( sizeof($MO->results) > 0 ) {
     				$updateQuery = file_get_contents('sql/updateMachinesInSemaforo.sql');
     				$DB->setQuery($updateQuery);
     				$DB->bind_vars(':test_dt',$MO->results[0]['TEST_DT']);
     				$DB->bind_vars(':update-date',$date = date("d-M-Y H:i"));
     				$DB->bind_vars(':id',$value['ID']);
     				$DB->exec();
    			} else {
    				$updateQuery = file_get_contents('sql/updateLastrunInSemaforo.sql');
    				$DB->setQuery($updateQuery);
    				$DB->bind_vars(':update-date',$date = date("d-M-Y H:i"));
    				$DB->bind_vars(':id',$value['ID']);
           			$DB->exec();
 			}
		}
		$MO->close();
	}

	$DB->close();

	$final = date("d-M-Y H:i:s");
	logToFile(sprintf("[x] Completado: inicio:%s, final:%s", $inicio, $final));
}



function debugQuery(){
/*
    Basicamente lo que hace es buscar los datos desde la base de datos
    y construye la cantidad de querys necesarios para poder hacer la
    busqueda de datos, es con fines de revision UNICAMENTE, en caso de
    que no encuentre nada de datos desde la aplicacion, entonces,
    usare esto para revisar contra SQLTools y ver que es lo que esta pasando

    El la peticion tiene que hacerse de la siguiente manera:

    toolbox.php?action=debugQuery?DB_ID=NOMBRE DE LA MAQUINA

    Return:
        Todos los querys que esten registrados en la base de datos para esta maquina
*/
	$DB_ID = $_GET['DB_ID'];
	echo "DB_ID: $DB_ID".PHP_EOL;
	$query = "select * from semaforo where db_id = '" . $DB_ID . "'";
	$DB = new MxApps();
	$DB->setQuery($query);
	$DB->exec();
	echo(print_r($DB->results,true).PHP_EOL);
	$connections = array(
		'mxoptix' => 'MxOptix',
		'mxapps'  => 'MxApps',
		'prodmx' => 'Dare',
		'dare_mrc'=>'MRC'
	);
	foreach ($DB->results as $key => $value) {
		// genero el query para la busqueda de datos
		$MO = new $connections[$value['DBCONNECTION']]();
		$infoQuery = file_get_contents('sql/getInfo.sql');
		echo "conexion: " . $value['DBCONNECTION'].PHP_EOL;
		$MO->setQuery($infoQuery);
		$MO->bind_vars(':db_id',$value['DB_ID']);
		$MO->bind_vars(':facility',$value['DBMACHINE']);
		$MO->bind_vars(':device',$value['DBDEVICE']);
		$MO->bind_vars(':test_dt',$value['DBDATE']);
		$MO->bind_vars(':table',$value['DBTABLE']);
		echo "Query:" . PHP_EOL;
		echo $MO->query . PHP_EOL;
		oci_free_statement($MO->statement);
		oci_close($MO->conn);
	}
	$DB->close();
}

function logToFile($content)
{
	file_put_contents('logs.txt', $content.PHP_EOL , FILE_APPEND);
}