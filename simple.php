<?php
require '../Slim/Slim.php';

if ( $_SERVER['REMOTE_ADDR'] != '192.168.1.147'){
    $local = false;
    include "../inc/database.php";
} else {
    $local = true;
}

$app = new Slim();

$app->get('/', 'semaforo' );
$app->get('/all', 'index' );
$app->get('/debug/:id', 'debug');
$app->get('/:id', 'retrieve' );
$app->put('/:id', 'update');
$app->delete('/:id', 'del');
$app->post('/','insert');


function semaforo(){
    global $local;
    $query = file_get_contents('sql/machines.semaforo.sql');
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
        // file_put_contents('filecache.txt', $json);
        
        $objects = json_decode($json, true);
        $objects = setStatus($objects);
        $output = grouper($objects);
        include "simple.templates/header.php";

        $liTags = file_get_contents('simple.templates/litags.php');

        print_r($output);

        include "simple.templates/footer.php";
    }
    if (!$local) {
        oci_free_statement($DB->statement);
        oci_close($DB->conn);
    }
}

function setStatus($objects)
{
    $actual = strtotime('now');
    foreach ($objects as $key => $value) {
        $tick = strtotime($value['LASTTICK']);
        // Esta es solo pa prueba de que funciona
        // $objects[$key]['STATUS'] = ((($actual - $tick)/60)/60)/24;
        if (  ($actual - $tick) < $value['CICLETIME']  ) {
            $status = 'green';
        } elseif ( ($actual - $tick) < 2*$value['CICLETIME']) {
            $status = 'yellow';
        } else {
            $status = 'red';
        }
        
        $objects[$key]['STATUS'] = $status;
    }
    return $objects;
}


function grouper($objects)
{
    // La intension de esta funcion es generar todos los agrupamientos necesarios
    // de una sola vez BU->AREA->PROCESS
    
    $group = array();
    foreach ($objects as $key => $value) {
        // Crea un arreglo tan profundo como se tenga que hacer
        if (!array_key_exists( $value['BU'] , $group)) {
            $group[$value['BU']]=array();
        } 
        if(!array_key_exists($value['AREA'] , $group[$value['BU']])){
            $group[$value['BU']][$value['AREA']]=array();
        }
        if(!array_key_exists($value['PROCESS'], $group[$value['BU']][$value['AREA']])){
            $group[$value['BU']][$value['AREA']][$value['PROCESS']]=array();
        }
        // Se le inserta el valor necesario hasta el final (con todas las llaves creadas)
        // muy eficiente!
        array_push($group[$value['BU']][$value['AREA']][$value['PROCESS']], $value);
    }

    return $group;
}

$app->run();
