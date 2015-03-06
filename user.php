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

if (isset($_POST)) {
	try {
		if( file_exists('count.txt') ){
			$lastId = file_get_contents('count.txt');
		} else {
			$lastId = 0;
		}
		$user = json_decode(file_get_contents("php://input"),true);
		$user['id']= $lastId + 1;
		// file_put_contents('count.txt', $lastId+1);
		file_put_contents("user." . ($lastId+1) . ".txt", json_encode($user));
		echo(json_encode($user));
	} catch (Exception $e) {
		echo $e;
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

function getSpecificMachine()
{
	// updateMachinesMxOptix();
	$query = file_get_contents('sql/machines.sql');
	$DB = new MxApps();
	$DB->setQuery($query. " where id = '" . $_GET['ID'] ."'");
	$DB->exec();
	if ($DB->json() == "[]") {
		throw new Exception("No arrojo datos la base de datos", 1);
	} else {
		echo $DB->json();
	}	
}

