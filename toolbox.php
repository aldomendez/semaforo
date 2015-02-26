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
	$query = file_get_contents('machines.sql');
	$DB = new MxApps();
	$DB->setQuery($query);
	$DB->exec();
	if ($DB->json() == "[]") {
		throw new Exception("No arrojo datos la base de datos", 1);
	} else {
		echo $DB->json();
	}	
}


/* 
function getResults()
{
	$query = file_get_contents('query.sql');
	$DB = new MxOptix();
	$DB->setQuery($query);
	$DB->bind_vars(':serial_num',$_GET['serial_num']);
	// echo $DB->query;
	$DB->exec();
	if ($DB->json() == "[]") {
		echo '[{"JOB":"' . $_GET['serial_num'] . '"}]';
	} else {
		echo $DB->json();
	}
}

function getLPN()
{
	if (file_exists($_GET['lpn'])) {
		echo file_get_contents($_GET['lpn']);
	} else {
		$query = file_get_contents('selectByLPN.sql');
		$DB = new MxOptix();
		$DB->setQuery($query);
		$DB->bind_vars(':lpn',$_GET['lpn']);
		$DB->exec();
		if ($DB->json() == "[]") {
			echo '[{"LPN":"' . $_GET['lpn'] . '"}]';
		} else {
			file_put_contents($_GET['lpn'], $DB->json());
			echo $DB->json();
		}
	}
} */